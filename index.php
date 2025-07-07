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

    if ($user['role'] == 'dosen') {
      $_SESSION['dosen_id'] = $user['dosen_id'] ?? null;
      header("Location: dashboard/dashboard_dosen.php");
      exit;
    } elseif ($user['role'] == 'mahasiswa') {
      if ($user['mahasiswa_id']) {
        $_SESSION['mahasiswa_id'] = $user['mahasiswa_id'];
        header("Location: dashboard/dashboard_mahasiswa.php");
        exit;
      } else {
        $error = "Akun mahasiswa tidak valid. Hubungi admin.";
      }
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
  <style>
    .login-box {
      background-color: #ffffff;
      padding: 40px 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      width: 450px;
      max-width: 100%;
      margin: 0 auto;
      position: absolute;
      left: 700px;
    }

    .login-box h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 20px;
      padding-bottom: 20px;
    }

    .input-group {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      background-color: #f1f5f9;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      padding: 10px;
    }

    .input-group .icon {
      margin-right: 10px;
    }

    .input-group input {
      border: none;
      outline: none;
      background: transparent;
      width: 100%;
      font-size: 14px;
    }

    .btn-login {
      width: 100%;
      padding: 10px;
      background-color: #2c3e50;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn-login:hover {
      background-color: #4f5f6e;
    }

    .error {
      color: red;
      font-size: 14px;
      text-align: center;
      margin-bottom: 10px;
    }

    .sidebar a {
      font-size: 16px;
    }

    .login-text {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 25px;
    }

    .input-group input {
      font-size: 16px;
      padding: 10px 12px;
    }

    .forgot-password {
      text-align: center;
      margin-top: 12px;
    }

    .forgot-password a {
      color: #1d4ed8;
      text-decoration: none;
      font-size: 14px;
    }

    .forgot-password a:hover {
      text-decoration: underline;
    }
  </style>
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
    <div class="sidebar">
      <a href="index.php" class="active">🏠 Beranda</a>
    </div>

    <div class="page-wrapper">
      <div style="display: flex; justify-content: center; align-items: center; min-height: 400px;">
        <div class="login-box">
          <h2>Login</h2>
          <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
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

            <div class="forgot-password">
              <a href="lupa_password.php">🔑 Lupa Password?</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
