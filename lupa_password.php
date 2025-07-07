<?php
session_start();
include 'koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = mysqli_real_escape_string($conn, $_POST['username']);

  $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
  $user = mysqli_fetch_assoc($query);

  if ($user) {
    // Simpan user ID sementara dalam session untuk proses selanjutnya
    $_SESSION['reset_user_id'] = $user['id'];
    header("Location: reset_password.php");
    exit;
  } else {
    $error = "Username tidak ditemukan.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lupa Password</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/layout.css">
  <style>
    .reset-box {
      max-width: 400px;
      margin: 80px auto;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .reset-box h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 20px;
    }

    .reset-box input {
      width: 100%;
      padding: 10px;
      margin: 10px 0 20px;
      border-radius: 6px;
      border: 1px solid #cbd5e1;
      font-size: 16px;
    }

    .reset-box button {
      width: 100%;
      padding: 10px;
      background-color: #2563eb;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }

    .reset-box button:hover {
      background-color: #1e40af;
    }

    .reset-box .error {
      color: red;
      text-align: center;
      font-size: 14px;
    }

    .reset-box a {
      display: block;
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
      color: #1e3a8a;
      text-decoration: none;
    }

    .reset-box a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="reset-box">
    <h2>Lupa Password</h2>

    <?php if ($error): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <label for="username">Masukkan Username Anda</label>
      <input type="text" name="username" id="username" required placeholder="Contoh: user123">

      <button type="submit">Lanjut</button>
    </form>

    <a href="index.php">🔙 Kembali ke Login</a>
  </div>
</body>
</html>
