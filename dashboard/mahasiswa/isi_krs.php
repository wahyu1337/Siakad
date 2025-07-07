<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
  header("Location: ../../index.php");
  exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$mahasiswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT jurusan_id FROM mahasiswa WHERE id = $mahasiswa_id"));
$jurusan_id = $mahasiswa['jurusan_id'] ?? 0;

$tahun_ajaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE status_aktif = 1"));
$id_tahun = $tahun_ajaran['id'];
$label_tahun = $tahun_ajaran['tahun_mulai'] . "/" . $tahun_ajaran['tahun_selesai'] . " - " . ucfirst($tahun_ajaran['semester']);

$error = '';
$success = '';

$ambil_q = mysqli_query($conn, "SELECT matakuliah_id, jadwal_id FROM krs WHERE mahasiswa_id = $mahasiswa_id AND tahun_ajaran_id = $id_tahun");
$jadwal_diambil = [];
$matkul_diambil = [];
$total_sks = 0;

while ($r = mysqli_fetch_assoc($ambil_q)) {
  $jadwal_diambil[] = $r['jadwal_id'];
  $matkul_diambil[] = $r['matakuliah_id'];
  $sks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sks FROM matakuliah WHERE id = " . $r['matakuliah_id']));
  $total_sks += $sks['sks'] ?? 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jadwal_id']) && is_array($_POST['jadwal_id'])) {
  $berhasil = 0;
  $gagal = 0;
  $sks_baru = 0;

  foreach ($_POST['jadwal_id'] as $jadwal_id) {
    $jadwal_id = intval($jadwal_id);
    $cek = mysqli_fetch_assoc(mysqli_query($conn, "
      SELECT matakuliah_id, kelas_id FROM jadwal 
      WHERE id = $jadwal_id AND tahun_ajaran_id = $id_tahun AND jurusan_id = $jurusan_id
    "));

    $matkul_id = $cek['matakuliah_id'] ?? 0;
    $kelas_id = $cek['kelas_id'] ?? 'NULL';
    
    $sks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sks FROM matakuliah WHERE id = $matkul_id"));
    $sks = $sks['sks'] ?? 0;

    if (!$matkul_id || in_array($matkul_id, $matkul_diambil)) {
      $gagal++;
    } elseif (($total_sks + $sks_baru + $sks) > 24) {
      $error = "Total SKS melebihi batas maksimum (24 SKS).";
      break;
    } else {
      $simpan = mysqli_query($conn, "
        INSERT INTO krs (mahasiswa_id, jadwal_id, matakuliah_id, kelas_id, tahun_ajaran_id, semester)
        VALUES ($mahasiswa_id, $jadwal_id, $matkul_id, $kelas_id, $id_tahun, 0)
      ");
      if ($simpan) {
        $matkul_diambil[] = $matkul_id;
        $jadwal_diambil[] = $jadwal_id;
        $sks_baru += $sks;
        $berhasil++;
      } else {
        $gagal++;
      }
    }
  }

  $total_sks += $sks_baru;

  if ($berhasil > 0) $success = "$berhasil mata kuliah berhasil ditambahkan.";
  if ($gagal > 0 && !$error) $error = "$gagal mata kuliah gagal ditambahkan.";
}

$jadwal_q = mysqli_query($conn, "
  SELECT j.id AS jadwal_id, mk.id AS matkul_id, mk.nama_mk, mk.sks, k.nama_kelas
  FROM jadwal j
  JOIN matakuliah mk ON j.matakuliah_id = mk.id
  JOIN kelas k ON j.kelas_id = k.id
  WHERE j.jurusan_id = $jurusan_id AND j.tahun_ajaran_id = $id_tahun
  ORDER BY mk.nama_mk ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Isi KRS</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/layout.css">
  <style>
    .container { padding: 20px; }
    .krs-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .krs-table th, .krs-table td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    .krs-table th { background-color: #f0f0f0; }
    .alert { padding: 10px; border-radius: 8px; margin-bottom: 15px; }
    .alert-success { background: #d1fae5; color: #065f46; }
    .alert-error { background: #fee2e2; color: #991b1b; }
    .btn { padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .btn:hover { background: #0056b3; }
  </style>
</head>
<body>
<header>
  <div class="header-left"><img src="../../logo.png" class="logo-fixed" alt="Logo"></div>
  <div class="header-center"><h1>Isi KRS Semester Aktif</h1></div>
</header>

<div class="main-content">
  <div class="sidebar">
    <a href="../dashboard_mahasiswa.php">🏠 Dashboard</a>
    <a href="krs_saya.php" class="active">📄 KRS</a>
    <a href="jadwal_saya.php">📅 Jadwal</a>
    <a href="nilai_saya.php">📝 Nilai</a>
    <form action="../../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
  </div>

  <div class="page-wrapper">
    <div class="container">
      <h3>Periode: <?= $label_tahun ?></h3>
      <p><strong>Total SKS Saat Ini: <?= $total_sks ?> / 24</strong></p>

      <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
      <?php if ($error): ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>

      <form method="post">
        <table class="krs-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Mata Kuliah</th>
              <th>SKS</th>
              <th>Kelas</th>
              <th>Pilih</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($jadwal_q) > 0):
              while ($row = mysqli_fetch_assoc($jadwal_q)):
                $jadwal_id = $row['jadwal_id'];
                $matkul_id = $row['matkul_id'];
                $status = '';

                if (in_array($jadwal_id, $jadwal_diambil)) {
                  $status = '<span style="color: green;">✓ Sudah Diambil</span>';
                } elseif (in_array($matkul_id, $matkul_diambil)) {
                  $status = '<span style="color: orange;">⚠️ Tidak Bisa Ambil Kelas Ini!</span>';
                } else {
                  $status = '<input type="checkbox" name="jadwal_id[]" value="' . $jadwal_id . '">';
                }
            ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                  <td><?= $row['sks'] ?></td>
                  <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                  <td><?= $status ?></td>
                </tr>
            <?php
              endwhile;
            else:
              echo "<tr><td colspan='5'>Tidak ada jadwal tersedia.</td></tr>";
            endif;
            ?>
          </tbody>
        </table>
        <br>
        <button type="submit" class="btn">💾 Simpan KRS</button>
        <a href="krs_saya.php" class="btn" style="background: gray;">← Kembali</a>
      </form>
    </div>
  </div>
</div>
</body>
</html>