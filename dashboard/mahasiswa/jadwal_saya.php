<?php
session_start();
include '../../koneksi.php';

// Cek login & role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
  header("Location: ../../index.php");
  exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];

// Info mahasiswa
$mhs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama, nim FROM mahasiswa WHERE id = $mahasiswa_id"));

// Tahun ajaran aktif
$tahun_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE status_aktif = 1"));
$tahun_ajaran_id_aktif = $tahun_aktif['id'] ?? 0;

// Ambil semua tahun ajaran
$tahun_ajaran_all = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY tahun_mulai DESC, semester DESC");

// Cek filter
$tahun_terpilih = isset($_GET['tahun_ajaran_id']) ? intval($_GET['tahun_ajaran_id']) : $tahun_ajaran_id_aktif;

// Ambil jadwal sesuai tahun ajaran
$query = mysqli_query($conn, "
  SELECT 
    mk.nama_mk, 
    d.nama AS dosen, 
    j.hari, 
    j.jam_mulai, 
    j.jam_selesai, 
    kls.nama_kelas,
    ta.tahun_mulai, ta.tahun_selesai, ta.semester
  FROM krs k
  JOIN matakuliah mk ON k.matakuliah_id = mk.id
  JOIN jadwal j ON j.matakuliah_id = mk.id AND j.kelas_id = k.kelas_id
  JOIN kelas kls ON k.kelas_id = kls.id
  JOIN dosen d ON j.dosen_id = d.id
  JOIN tahun_ajaran ta ON k.tahun_ajaran_id = ta.id
  WHERE k.mahasiswa_id = $mahasiswa_id AND k.tahun_ajaran_id = $tahun_terpilih
  ORDER BY FIELD(j.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), j.jam_mulai
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Jadwal Saya</title>
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
    <div class="header-left">
      <img src="../../logo.png" class="logo-fixed" alt="Logo">
    </div>
    <div class="header-center">
      <h1>Jadwal Kuliah</h1>
      <p><?= htmlspecialchars($mhs['nama']) ?> | NIM: <?= htmlspecialchars($mhs['nim']) ?></p>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="../dashboard_mahasiswa.php">🏠 Dashboard</a>
      <a href="krs_saya.php">📄 KRS</a>
      <a href="jadwal_saya.php" class="active">📅 Jadwal Kuliah</a>
      <a href="nilai_saya.php">📝 Nilai</a>
      <a href="profil/profile_mahasiswa.php">👤 Profil</a>
      <form action="../../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Jadwal Saya</h2>

        <form method="get" style="margin-bottom: 20px;">
          <label for="tahun_ajaran_id">Tahun Ajaran:</label>
          <select name="tahun_ajaran_id" class="form-control" onchange="this.form.submit()">
            <?php while ($ta = mysqli_fetch_assoc($tahun_ajaran_all)): 
              $label = $ta['tahun_mulai'].'/'.$ta['tahun_selesai'].' - '.ucfirst($ta['semester']);
              $selected = $ta['id'] == $tahun_terpilih ? 'selected' : '';
            ?>
              <option value="<?= $ta['id'] ?>" <?= $selected ?>><?= $label ?></option>
            <?php endwhile; ?>
          </select>
        </form>

        <table>
          <thead>
            <tr>
              <th>Hari</th>
              <th>Jam</th>
              <th>Mata Kuliah</th>
              <th>Dosen</th>
              <th>Kelas</th>
              <th>Semester</th>
              <th>Tahun Ajaran</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($query) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                  <td><?= $row['hari'] ?></td>
                  <td><?= substr($row['jam_mulai'], 0, 5) ?> - <?= substr($row['jam_selesai'], 0, 5) ?></td>
                  <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                  <td><?= htmlspecialchars($row['dosen']) ?></td>
                  <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                  <td><?= $row['semester'] ?></td>
                  <td><?= $row['tahun_mulai'] ?>/<?= $row['tahun_selesai'] ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="7">Tidak ada jadwal ditemukan untuk tahun ajaran ini.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
