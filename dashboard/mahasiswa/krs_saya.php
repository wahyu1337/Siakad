<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
  header("Location: ../../index.php");
  exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$mhs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama, nim FROM mahasiswa WHERE id = $mahasiswa_id"));

$tahun_ajaran = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY tahun_mulai DESC, semester DESC");

// Filter dari URL (GET)
$filter_tahun = isset($_GET['tahun_ajaran_id']) && $_GET['tahun_ajaran_id'] != '' ? intval($_GET['tahun_ajaran_id']) : 0;

// Ambil Tahun Ajaran Aktif
$tahun_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE status_aktif = 1"));
$id_ta_aktif = $tahun_aktif['id'];
$label_aktif = $tahun_aktif['tahun_mulai'] . "/" . $tahun_aktif['tahun_selesai'] . " - " . ucfirst($tahun_aktif['semester']);

// Jika filter belum dipilih, pakai tahun aktif
$tahun_terpilih = $filter_tahun ? $filter_tahun : $id_ta_aktif;

// Aksi hapus (hanya untuk semester aktif)
if (isset($_GET['hapus'])) {
  $hapus_id = intval($_GET['hapus']);
  mysqli_query($conn, "DELETE FROM krs WHERE id = $hapus_id AND mahasiswa_id = $mahasiswa_id AND tahun_ajaran_id = $id_ta_aktif");
  header("Location: krs_saya.php?tahun_ajaran_id=$id_ta_aktif");
  exit;
}

// Ambil data KRS
$krs_q = mysqli_query($conn, "
  SELECT 
    krs.id AS krs_id, 
    mk.kode_mk, mk.nama_mk, mk.sks,
    krs.tahun_ajaran_id, 
    ta.semester, ta.tahun_mulai, ta.tahun_selesai,
    d.nama AS nama_dosen,
    j.hari, j.jam_mulai, j.jam_selesai,
    k.nama_kelas,
    n.nilai_angka
  FROM krs
  JOIN matakuliah mk ON krs.matakuliah_id = mk.id
  JOIN tahun_ajaran ta ON krs.tahun_ajaran_id = ta.id
  LEFT JOIN jadwal j ON krs.jadwal_id = j.id
  LEFT JOIN dosen d ON j.dosen_id = d.id
  LEFT JOIN kelas k ON j.kelas_id = k.id
  LEFT JOIN nilai n ON n.krs_id = krs.id
  WHERE krs.mahasiswa_id = $mahasiswa_id AND krs.tahun_ajaran_id = $tahun_terpilih
  ORDER BY mk.nama_mk ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>KRS Saya</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/layout.css">
  <link rel="stylesheet" href="../../css/dashboard.css">
  <style>
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    th { background-color: #e5f4ff; }
    .form-control { padding: 6px 10px; margin-right: 10px; }
    .btn { padding: 6px 12px; background: #007bff; color: white; border: none; cursor: pointer; border-radius: 4px; }
    .btn:hover { background: #0056b3; }
  </style>
</head>
<body>
<header>
  <div class="header-left"><img src="../../logo.png" class="logo-fixed" alt="Logo"></div>
  <div class="header-center">
    <h1>Kartu Rencana Studi</h1>
    <p><?= htmlspecialchars($mhs['nama']) ?> | NIM: <?= htmlspecialchars($mhs['nim']) ?></p>
  </div>
</header>
<div class="main-content">
  <div class="sidebar">
    <a href="../dashboard_mahasiswa.php">🏠 Dashboard</a>
    <a href="krs_saya.php" class="active">📄 KRS</a>
    <a href="jadwal_saya.php">📅 Jadwal Kuliah</a>
    <a href="nilai_saya.php">📝 Nilai</a>
    <a href="profil/profile_mahasiswa.php">👤 Profil</a>
    <form action="../../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
  </div>
  <div class="page-wrapper">
    <div class="container">
      <h2 class="form-title">Data KRS Mahasiswa</h2>

      <form method="get" style="margin-bottom: 20px;">
        <label for="tahun_ajaran_id">Filter Tahun Ajaran:</label>
        <select name="tahun_ajaran_id" class="form-control" onchange="this.form.submit()">
          <option value="">-- Semua --</option>
          <?php while ($t = mysqli_fetch_assoc($tahun_ajaran)): ?>
            <?php
              $label = $t['tahun_mulai'] . '/' . $t['tahun_selesai'] . ' - ' . ucfirst($t['semester']);
              $selected = $filter_tahun == $t['id'] ? 'selected' : '';
            ?>
            <option value="<?= $t['id'] ?>" <?= $selected ?>><?= $label ?></option>
          <?php endwhile; ?>
        </select>
      </form>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Mata Kuliah</th>
            <th>Kelas</th>
            <th>Tahun</th>
            <th>Semester</th>
            <th>Nilai</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($krs_q) > 0): ?>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($krs_q)): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                <td><?= $row['tahun_mulai'] ?>/<?= $row['tahun_selesai'] ?></td>
                <td><?= $row['semester'] ?></td>
                <td><?= is_null($row['nilai_angka']) ? '-' : $row['nilai_angka'] ?></td>            
                <td>
                  <?php if ($row['tahun_ajaran_id'] == $id_ta_aktif): ?>
                    <a href="?hapus=<?= $row['krs_id'] ?>" onclick="return confirm('Hapus mata kuliah ini?')">🗑️ Hapus</a>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="6">Tidak ada data KRS ditemukan.</td></tr>
          <?php endif; ?>
        </tbody>

        <?php

        // Hitung total SKS
          mysqli_data_seek($krs_q, 0); // Reset pointer
          $total_sks = 0;
          while ($row = mysqli_fetch_assoc($krs_q)) {
            $total_sks += (int)$row['sks'];
          }
          ?>

          <tfoot>
            <tr>
              <td colspan="5" style="text-align: right;"><strong>Total SKS</strong></td>
              <td colspan="2" style="text-align: left;"><?= $total_sks ?> SKS</td>
            </tr>
          </tfoot>

        </table>

      <br><a href="isi_krs.php" class="btn">+ Isi KRS </a>
    </div>
  </div>
</div>
</body>
</html>