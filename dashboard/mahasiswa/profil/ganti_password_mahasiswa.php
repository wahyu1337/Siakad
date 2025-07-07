<?php
session_start();
include '../../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../../index.php");
    exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$username = $_SESSION['username'];

// Ambil data mahasiswa
$query = mysqli_query($conn, "SELECT nama FROM mahasiswa WHERE id = $mahasiswa_id");
$mahasiswa = mysqli_fetch_assoc($query);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_lama = mysqli_real_escape_string($conn, $_POST['password_lama']);
    $password_baru = mysqli_real_escape_string($conn, $_POST['password_baru']);
    $konfirmasi_password = mysqli_real_escape_string($conn, $_POST['konfirmasi_password']);

    // Cek password lama dari tabel users
    $cek = mysqli_query($conn, "SELECT password FROM users WHERE username = '$username'");
    $user = mysqli_fetch_assoc($cek);

    if ($user && password_verify($password_lama, $user['password'])) {
        if ($password_baru === $konfirmasi_password) {
            $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
            $update = mysqli_query($conn, "UPDATE users SET password = '$password_hash' WHERE username = '$username'");
            $success = "Password berhasil diubah.";
        } else {
            $error = "Konfirmasi password tidak cocok.";
        }
    } else {
        $error = "Password lama salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Ganti Password Mahasiswa</title>
  <link rel="stylesheet" href="../../../css/style.css">
  <link rel="stylesheet" href="../../../css/layout.css">
  <link rel="stylesheet" href="../../../css/ganti_password.css">
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../../../logo.png" class="logo-fixed" alt="Logo">
    </div>
    <div class="header-center">
      <h1>Profil Mahasiswa</h1>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="../../dashboard_mahasiswa.php">🏠 Dashboard</a>
      <a href="../krs_saya.php">📄 KRS</a>
      <a href="../jadwal_saya.php">📅 Jadwal</a>
      <a href="../nilai_saya.php">📝 Nilai</a>
      <a href="profile_mahasiswa.php">👤 Profil</a>
      <form action="../../../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Ganti Password</h2>
        <p style="text-align:center; margin-top:-10px; color: #334155;">
          <strong><?= htmlspecialchars($mahasiswa['nama']) ?></strong><br>
          <small><?= htmlspecialchars($username) ?></small>
        </p>

        <?php if ($success): ?>
          <div class="success"><?= $success ?></div>
        <?php elseif ($error): ?>
          <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" class="form-ganti-password">
          <label>Password Lama:</label>
          <input type="password" name="password_lama" required>

          <label>Password Baru:</label>
          <input type="password" name="password_baru" required>

          <label>Konfirmasi Password Baru:</label>
          <input type="password" name="konfirmasi_password" required>

          <div style="display: flex; gap: 10px; margin-top: 15px;">
            <button type="submit" class="button">🔐 Simpan Password</button>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</body>
</html>