<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
  header("Location: ../../index.php");
  exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$mhs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama, nim FROM mahasiswa WHERE id = $mahasiswa_id"));

$query = mysqli_query($conn, "
  SELECT 
    mk.nama_mk, 
    n.nilai_angka, 
    ta.tahun_mulai, 
    ta.tahun_selesai, 
    ta.semester
  FROM nilai n
  JOIN matakuliah mk ON n.matakuliah_id = mk.id
  JOIN krs k ON k.matakuliah_id = mk.id AND k.mahasiswa_id = n.mahasiswa_id
  JOIN tahun_ajaran ta ON k.tahun_ajaran_id = ta.id
  WHERE n.mahasiswa_id = $mahasiswa_id
  ORDER BY ta.tahun_mulai DESC, ta.semester DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Nilai Saya</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/layout.css">
  <link rel="stylesheet" href="../../css/dashboard.css">
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../../logo.png" alt="Logo" class="logo-fixed">
    </div>
    <div class="header-center">
      <h1>Nilai Akademik Mahasiswa</h1>
      <p><?= htmlspecialchars($mhs['nama']) ?> | NIM: <?= htmlspecialchars($mhs['nim']) ?></p>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="../dashboard_mahasiswa.php">🏠 Dashboard</a>
      <a href="krs_saya.php">📄 KRS</a>
      <a href="jadwal_saya.php">📅 Jadwal Kuliah</a>
      <a href="nilai_saya.php" class="active">📝 Nilai</a>
      <form action="../../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Daftar Nilai Saya</h2>
        <table>
          <thead>
            <tr>
              <th>Mata Kuliah</th>
              <th>Nilai</th>
              <th>Tahun Ajaran</th>
              <th>Semester</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($query) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                  <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                  <td><?= $row['nilai_angka'] ?></td>
                  <td><?= "{$row['tahun_mulai']}/{$row['tahun_selesai']}" ?></td>
                  <td><?= $row['semester'] ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="4">Belum ada nilai yang tersedia.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
