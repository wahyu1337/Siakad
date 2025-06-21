<?php
session_start();
include 'koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['role'])) {
  if ($_SESSION['role'] == 'admin') {
    header("Location: dashboard/admin_dashboard.php");
    exit;
  } elseif ($_SESSION['role'] == 'dosen') {
    header("Location: dashboard/dashboard_dosen.php");
    exit;
  } elseif ($_SESSION['role'] == 'mahasiswa') {
    header("Location: dashboard/dashboard_mahasiswa.php");
    exit;
  }
}

// Proses login
if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = $_POST['password'];

  $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
  $user = mysqli_fetch_assoc($query);

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    // Simpan dosen_id atau mahasiswa_id ke session jika ada
    if ($user['role'] == 'dosen') {
      $_SESSION['dosen_id'] = $user['dosen_id'] ?? null;
      header("Location: dashboard/dashboard_dosen.php");
      exit;
    } elseif ($user['role'] == 'mahasiswa') {
      $_SESSION['mahasiswa_id'] = $user['mahasiswa_id'] ?? null;
      header("Location: dashboard/dashboard_mahasiswa.php");
      exit;
    } elseif ($user['role'] == 'admin') {
      header("Location: dashboard/admin_dashboard.php");
      exit;
    }
  } else {
    $error = "Username atau password salah!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Beranda - Sistem Akademik</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/layout.css">
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <header>
    <div class="header-left">
      <img src="logo.png" class="logo-fixed" alt="Logo">
    </div>
    <div class="header-center">
      <h1>Sistem Akademik</h1>
      <p>Selamat datang di Sistem Informasi Akademik Universitas Handayani Makassar.</p>
    </div>
  </header>

  <div class="main-content">
    <div class="page-wrapper">
      <div class="container" style="text-align: center;">
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <div class="login-box" style="margin-top: 30px;">
          <h2 class="login-text">Login</h2>
          <form method="post">
            <div class="input-group">
              <span class="icon">👤</span>
              <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
              <span class="icon">🔒</span>
              <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn-login">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
