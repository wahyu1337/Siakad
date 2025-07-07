<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

// Query gabungan antara matakuliah dan jurusan
$query = mysqli_query($conn, "
  SELECT mk.id, mk.nama_mk, mk.semester, j.nama_jurusan
  FROM matakuliah mk
  JOIN jurusan j ON mk.jurusan_id = j.id
  ORDER BY mk.semester ASC, mk.nama_mk ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Mata Kuliah</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/layout.css">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../logo.png" alt="Logo" class="logo-fixed">
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
      <a href="data_matakuliah.php" class="active">📖 Mata Kuliah</a>
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
        <h2 class="form-title">Data Mata Kuliah</h2>
        <a href="../penambahan/tambah_matakuliah.php" class="button">+ Tambah Mata Kuliah</a>

        <table>
          <thead>
            <tr>
              <th>Nama Mata Kuliah</th>
              <th>Semester</th>
              <th>Jurusan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
              <tr>
                <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                <td><?= htmlspecialchars($row['semester']) ?></td>
                <td><?= htmlspecialchars($row['nama_jurusan']) ?></td>
                <td class="action-links">
                  <a href="../aksi/matakuliah/edit_matakuliah.php?id=<?= $row['id'] ?>">Edit</a> |
                  <a href="../aksi/matakuliah/hapus_matakuliah.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>