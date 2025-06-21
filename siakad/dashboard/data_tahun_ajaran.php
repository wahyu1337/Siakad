<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

$query = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY tahun_mulai DESC, semester ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Tahun Ajaran</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/layout.css">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../logo.png" class="logo-fixed" alt="Logo">
    </div>
    <div class="header-center">
      <h1>Sistem Akademik - Admin</h1>
      <p>Halo, <strong><?= $_SESSION['username'] ?></strong> (Admin)</p>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="admin_dashboard.php">🏠 Dashboard</a>
      <a href="data_mahasiswa.php">👨‍🎓 Mahasiswa</a>
      <a href="data_dosen.php">👨‍🏫 Dosen</a>
      <a href="data_jurusan.php">📚 Jurusan</a>
      <a href="data_matakuliah.php">📖 Mata Kuliah</a>
      <a href="data_kelas.php">🏫 Kelas</a>
      <a href="data_jadwal.php">🗓 Jadwal</a>
      <a href="data_nilai.php">📝 Nilai</a>
      <a href="data_tahun_ajaran.php" class="active">📆 Tahun Ajaran</a>
      <a href="data_users.php">🔐 User Login</a>
      <form action="../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Data Tahun Ajaran</h2>

        <a href="../penambahan/tambah_tahun_ajaran.php" class="button">+ Tambah Tahun Ajaran</a>

        <table>
          <thead>
            <tr>
              <th>Tahun Mulai</th>
              <th>Tahun Selesai</th>
              <th>Semester</th>
              <th>Status Aktif</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
              <tr>
                <td><?= htmlspecialchars($row['tahun_mulai']) ?></td>
                <td><?= htmlspecialchars($row['tahun_selesai']) ?></td>
                <td><?= htmlspecialchars($row['semester']) ?></td>
                <td><?= $row['status_aktif'] ? 'Aktif' : 'Tidak Aktif' ?></td>
                <td class="action-links">
                  <a href="../aksi/tahun_ajaran/edit_tahun_ajaran.php?id=<?= $row['id'] ?>">Edit</a> |
                  <a href="../aksi/tahun_ajaran/hapus_tahun_ajaran.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus tahun ajaran ini?')">Hapus</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
