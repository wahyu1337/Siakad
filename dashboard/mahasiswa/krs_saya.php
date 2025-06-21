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
  SELECT k.id, mk.nama_mk, kls.nama_kelas, ta.tahun_mulai, ta.tahun_selesai, ta.semester
  FROM krs k
  JOIN matakuliah mk ON k.matakuliah_id = mk.id
  JOIN kelas kls ON k.kelas_id = kls.id
  JOIN tahun_ajaran ta ON k.tahun_ajaran_id = ta.id
  WHERE k.mahasiswa_id = $mahasiswa_id
  ORDER BY ta.tahun_mulai DESC, ta.semester DESC
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
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../../logo.png" alt="Logo" class="logo-fixed">
    </div>
    <div class="header-center">
      <h1>Kartu Rencana Studi (KRS)</h1>
      <p><?= htmlspecialchars($mhs['nama']) ?> | NIM: <?= htmlspecialchars($mhs['nim']) ?></p>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="../dashboard_mahasiswa.php">🏠 Dashboard</a>
      <a href="krs_saya.php" class="active">📄 KRS</a>
      <a href="jadwal_saya.php">📅 Jadwal Kuliah</a>
      <a href="nilai_saya.php">📝 Nilai</a>
      <form action="../../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Daftar Mata Kuliah yang Diambil</h2>
        <a href="isi_krs.php" class="button">+ Tambah KRS</a>
        <table>
          <thead>
            <tr>
              <th>Mata Kuliah</th>
              <th>Kelas</th>
              <th>Tahun Ajaran</th>
              <th>Semester</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($query) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                  <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                  <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                  <td><?= "{$row['tahun_mulai']}/{$row['tahun_selesai']}" ?></td>
                  <td><?= $row['semester'] ?></td>
                  <td class="action-links">
                    <a href="isi_krs.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="../../aksi/krs/hapus_krs_mahasiswa.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="5">Belum ada KRS yang diinput.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
