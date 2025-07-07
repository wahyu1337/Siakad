<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

$query = mysqli_query($conn, "SELECT m.id, m.nim, m.nama, j.nama_jurusan, m.foto FROM mahasiswa m JOIN jurusan j ON m.jurusan_id = j.id ORDER BY m.nama ASC");

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Mahasiswa</title>
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
      <a href="data_mahasiswa.php" class="active">👨‍🎓 Mahasiswa</a>
      <a href="data_dosen.php">👨‍🏫 Dosen</a>
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
        <h2 class="form-title">Data Mahasiswa</h2>
        <a href="../penambahan/tambah_mahasiswa.php" class="button">+ Tambah Mahasiswa</a>
        <table>
          <thead>
            <tr>
              <th>Foto</th>
              <th>NIM</th>
              <th>Nama</th>
              <th>Jurusan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
              <td>
                <?php if (!empty($row['foto'])): ?>
                  <img src="../uploads/mahasiswa/<?= htmlspecialchars($row['foto']) ?>" alt="Foto" width="50" height="50" style="object-fit:cover; border-radius:50%;">
                <?php else: ?>
                  <span style="color:#999;">-</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($row['nim']) ?></td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td><?= htmlspecialchars($row['nama_jurusan']) ?></td>
              <td class="action-links">
                <a href="../aksi/mahasiswa/edit_mahasiswa.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="../aksi/mahasiswa/hapus_mahasiswa.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus data mahasiswa ini?')">Hapus</a>
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
