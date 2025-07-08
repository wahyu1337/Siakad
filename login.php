<?php
session_start();
include 'koneksi.php';

// Jika sudah login, langsung redirect ke dashboard sesuai role
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
  
    if ($user['role'] == 'admin') {
      header("Location: dashboard/admin_dashboard.php");
      exit;
    }
  
    if ($user['role'] == 'dosen') {
      $_SESSION['dosen_id'] = $user['dosen_id']; // ✅ diset sebelum redirect
      header("Location: dashboard/dashboard_dosen.php");
      exit;
    }
  
    if ($user['role'] == 'mahasiswa') {
      $_SESSION['mahasiswa_id'] = $user['mahasiswa_id'];
      header("Location: dashboard/dashboard_mahasiswa.php");
      exit;
    }
  }
}  
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Sistem Akademik</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/layout.css">
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <div class="login-box">
    <h3>Login</h3>
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
    </form>
  </div>
</body>
</html>
