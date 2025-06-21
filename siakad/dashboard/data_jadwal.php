<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

$query = mysqli_query($conn, "
  SELECT j.id, j.hari, j.jam_mulai, j.jam_selesai, d.nama AS nama_dosen, mk.nama_mk, k.nama_kelas,
         CONCAT(ta.tahun_mulai, '/', ta.tahun_selesai, ' ', ta.semester) AS tahun_ajaran
  FROM jadwal j
  JOIN dosen d ON j.dosen_id = d.id
  JOIN matakuliah mk ON j.matakuliah_id = mk.id
  JOIN kelas k ON j.kelas_id = k.id
  JOIN tahun_ajaran ta ON j.tahun_ajaran_id = ta.id
  ORDER BY j.hari, j.jam_mulai ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Jadwal</title>
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
      <a href="data_jadwal.php" class="active">🗓 Jadwal</a>
      <a href="data_nilai.php">📝 Nilai</a>
      <a href="data_tahun_ajaran.php">📆 Tahun Ajaran</a>
      <a href="data_users.php">🔐 User Login</a>
      <form action="../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Data Jadwal</h2>

        <a href="../penambahan/tambah_jadwal.php" class="button">+ Tambah Jadwal</a>

        <table>
          <thead>
            <tr>
              <th>Hari</th>
              <th>Jam</th>
              <th>Dosen</th>
              <th>Mata Kuliah</th>
              <th>Kelas</th>
              <th>Tahun Ajaran</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
              <tr>
                <td><?= htmlspecialchars($row['hari']) ?></td>
                <td><?= htmlspecialchars($row['jam_mulai']) ?> - <?= htmlspecialchars($row['jam_selesai']) ?></td>
                <td><?= htmlspecialchars($row['nama_dosen']) ?></td>
                <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                <td><?= htmlspecialchars($row['tahun_ajaran']) ?></td>
                <td class="action-links">
                  <a href="../aksi/jadwal/edit_jadwal.php?id=<?= $row['id'] ?>">Edit</a> |
                  <a href="../aksi/jadwal/hapus_jadwal.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus jadwal ini?')">Hapus</a>
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
