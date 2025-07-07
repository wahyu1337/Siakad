<?php
session_start();
include '../../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
  header("Location: ../../../index.php");
  exit;
}

$dosen_id = $_SESSION['dosen_id'];
$result = mysqli_query($conn, "SELECT * FROM dosen WHERE id = $dosen_id");
$dosen = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Dosen</title>
  <link rel="stylesheet" href="../../../css/style.css">
  <link rel="stylesheet" href="../../../css/layout.css">
  <link rel="stylesheet" href="../../../css/profil_dosen.css">
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../../../logo.png" class="logo-fixed" alt="Logo">
    </div>
    <div class="header-center">
      <h1>Profil Dosen</h1>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <div class="sidebar-user">
        <strong><?= htmlspecialchars($dosen['nama']) ?></strong>
        <small><?= htmlspecialchars($dosen['email']) ?></small>
      </div>
      <a href="../../dashboard_dosen.php">🏠 Dashboard</a>
      <a href="../jadwal_saya.php">📅 Jadwal Saya</a>
      <a href="../input_nilai.php">📝 Input Nilai</a>
      <a href="profile_dosen.php" class="active">👤 Profil</a>
      <form action="../../../logout.php" method="post">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="profil-container">
        <h2>Edit Profil</h2>
        <form class="profil-form" method="post" action="proses_update_profil_dosen.php">
          <label>Nama:</label>
          <input type="text" name="nama" value="<?= htmlspecialchars($dosen['nama']) ?>" required>

          <label>Email:</label>
          <input type="email" name="email" value="<?= htmlspecialchars($dosen['email']) ?>" required>

          <label>Bidang:</label>
          <input type="text" name="bidang" value="<?= htmlspecialchars($dosen['bidang']) ?>" required>

          <button type="submit">💾 Simpan Perubahan</button>
        </form>

        <br>
        <hr>
        <br>
        <a href="ganti_password_dosen.php" class="btn-password">🔐 Ganti Password</a>
      </div>
    </div>
  </div>
</body>
</html>