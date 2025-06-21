<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

$query = mysqli_query($conn, "SELECT * FROM dosen ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Dosen</title>
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
      <a href="data_dosen.php" class="active">👨‍🏫 Dosen</a>
      <a href="data_jurusan.php">📚 Jurusan</a>
      <a href="data_matakuliah.php">📖 Mata Kuliah</a>
      <a href="data_kelas.php">🏫 Kelas</a>
      <a href="data_jadwal.php">🗓 Jadwal</a>
      <a href="data_nilai.php">📝 Nilai</a>
      <a href="data_tahun_ajaran.php">📆 Tahun Ajaran</a>
      <a href="data_users.php">🔐 User Login</a>
      <form action="../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Data Dosen</h2>
        <a href="../penambahan/tambah_dosen.php" class="button">+ Tambah Dosen</a>

        <table>
          <thead>
            <tr>
              <th>NIP</th>
              <th>Nama</th>
              <th>Jenis Kelamin</th>
              <th>Email</th>
              <th>Bidang</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
              <tr>
                <td><?= htmlspecialchars($row['nip']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['bidang']) ?></td>
                <td class="action-links">
                  <a href="../aksi/dosen/edit_dosen.php?id=<?= $row['id'] ?>">Edit</a> |
                  <a href="../aksi/dosen/hapus_dosen.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus data dosen ini?')">Hapus</a>
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
