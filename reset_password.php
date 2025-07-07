<?php
session_start();
include 'koneksi.php';

// Pastikan user ID dari proses lupa password tersedia
if (!isset($_SESSION['reset_user_id'])) {
  header("Location: lupa_password.php");
  exit;
}

$user_id = $_SESSION['reset_user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $password = $_POST['password'];
  $konfirmasi = $_POST['konfirmasi'];

  if (strlen($password) < 3) {
    $error = "Password minimal 3 karakter.";
  } elseif ($password !== $konfirmasi) {
    $error = "Password dan konfirmasi tidak cocok.";
  } else {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $update = mysqli_query($conn, "UPDATE users SET password = '$password_hash' WHERE id = $user_id");

    if ($update) {
      unset($_SESSION['reset_user_id']); // hapus session reset
      $success = "Password berhasil direset. Silakan login.";
    } else {
      $error = "Terjadi kesalahan saat menyimpan password.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/layout.css">
  <style>
    .reset-container {
      max-width: 420px;
      margin: 80px auto;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .reset-container h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 20px;
    }

    .reset-container input {
      width: 100%;
      padding: 10px;
      margin: 10px 0 20px;
      border-radius: 6px;
      border: 1px solid #cbd5e1;
      font-size: 16px;
    }

    .reset-container button {
      width: 100%;
      padding: 10px;
      background-color: #10b981;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }

    .reset-container button:hover {
      background-color: #059669;
    }

    .error {
      color: red;
      font-size: 14px;
      text-align: center;
    }

    .success {
      color: green;
      font-size: 14px;
      text-align: center;
    }

    .reset-container a {
      display: block;
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
      color: #1e3a8a;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="reset-container">
    <h2>Reset Password</h2>

    <?php if ($error): ?>
      <div class="error"><?= $error ?></div>
    <?php elseif ($success): ?>
      <div class="success"><?= $success ?></div>
      <a href="index.php">🔐 Kembali ke Login</a>
    <?php else: ?>
      <form method="post">
        <label for="password">Password Baru</label>
        <input type="password" name="password" id="password" required placeholder="Minimal 3 karakter">

        <label for="konfirmasi">Konfirmasi Password</label>
        <input type="password" name="konfirmasi" id="konfirmasi" required>

        <button type="submit">Reset Password</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
