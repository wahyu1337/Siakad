<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

// Ambil data kelas dan jurusan
$query = mysqli_query($conn, "
  SELECT k.id, k.nama_kelas, j.nama_jurusan
  FROM kelas k
  JOIN jurusan j ON k.jurusan_id = j.id
  ORDER BY k.nama_kelas ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Kelas</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/layout.css">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
  <!-- HEADER -->
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
    <!-- SIDEBAR -->
    <div class="sidebar">
      <a href="admin_dashboard.php">🏠 Dashboard</a>
      <a href="data_mahasiswa.php">👨‍🎓 Mahasiswa</a>
      <a href="data_dosen.php">👨‍🏫 Dosen</a>
      <a href="data_jurusan.php">📚 Jurusan</a>
      <a href="data_matakuliah.php">📖 Mata Kuliah</a>
      <a href="data_kelas.php" class="active">🏫 Kelas</a>
      <a href="data_jadwal.php">🗓 Jadwal</a>
      <a href="data_nilai.php">📝 Nilai</a>
      <a href="data_tahun_ajaran.php">📆 Tahun Ajaran</a>
      <a href="data_users.php">🔐 User Login</a>
      <form action="../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <!-- KONTEN -->
    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Data Kelas</h2>
        <a href="../penambahan/tambah_kelas.php" class="button">+ Tambah Kelas</a>
        <table>
          <thead>
            <tr>
              <th>Nama Kelas</th>
              <th>Jurusan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
              <tr>
                <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                <td><?= htmlspecialchars($row['nama_jurusan']) ?></td>
                <td class="action-links">
                  <a href="../aksi/kelas/edit_kelas.php?id=<?= $row['id'] ?>">Edit</a> |
                  <a href="../aksi/kelas/hapus_kelas.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus kelas ini?')">Hapus</a>
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
