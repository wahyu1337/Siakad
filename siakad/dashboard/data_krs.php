<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

$query = mysqli_query($conn, "
  SELECT 
    k.id,
    m.nim,
    m.nama AS nama_mahasiswa,
    mk.nama_mk,
    krs_kelas.nama_kelas,
    ta.tahun_mulai,
    ta.tahun_selesai,
    ta.semester
  FROM krs k
  JOIN mahasiswa m ON k.mahasiswa_id = m.id
  JOIN matakuliah mk ON k.matakuliah_id = mk.id
  JOIN kelas krs_kelas ON k.kelas_id = krs_kelas.id
  JOIN tahun_ajaran ta ON k.tahun_ajaran_id = ta.id
  ORDER BY m.nama ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data KRS</title>
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
      <a href="data_nilai.php">📝 Nilai</a>
      <a href="data_krs.php" class="active">📄 KRS</a>
      <a href="data_tahun_ajaran.php">📆 Tahun Ajaran</a>
      <a href="data_users.php">🔐 User Login</a>
      <form action="../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Data KRS</h2>

        <a href="../penambahan/tambah_krs.php" class="button">+ Tambah KRS</a>

        <table>
          <thead>
            <tr>
              <th>NIM</th>
              <th>Nama Mahasiswa</th>
              <th>Mata Kuliah</th>
              <th>Kelas</th>
              <th>Tahun Ajaran</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
              <tr>
                <td><?= htmlspecialchars($row['nim']) ?></td>
                <td><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                <td><?= $row['tahun_mulai'] ?>/<?= $row['tahun_selesai'] ?> <?= $row['semester'] ?></td>
                <td class="action-links">
                  <a href="../aksi/krs/edit_krs.php?id=<?= $row['id'] ?>">Edit</a> |
                  <a href="../aksi/krs/hapus_krs.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus data KRS ini?')">Hapus</a>
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
