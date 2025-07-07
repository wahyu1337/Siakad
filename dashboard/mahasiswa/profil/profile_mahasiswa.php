<?php
session_start();
include '../../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
  header("Location: ../../../index.php");
  exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'] ?? null;
if (!$mahasiswa_id) {
  echo "<p style='color:red; text-align:center;'>Akun Anda tidak memiliki ID mahasiswa yang valid. Silakan hubungi admin.</p>";
  exit;
}

$result = mysqli_query($conn, "SELECT m.nama, m.nim, m.tanggal_lahir, j.nama_jurusan
                                FROM mahasiswa m
                                JOIN jurusan j ON m.jurusan_id = j.id
                                WHERE m.id = $mahasiswa_id");

$mahasiswa = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Mahasiswa</title>
  <link rel="stylesheet" href="../../../css/style.css">
  <link rel="stylesheet" href="../../../css/layout.css">
  <style>
    .profil-container {
      max-width: 600px;
      margin: 0 auto;
      background-color: white;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .profil-container h2 {
      font-size: 22px;
      text-align: center;
      margin-bottom: 20px;
      color: #1e293b;
    }

    .profil-container label {
      display: block;
      margin-top: 14px;
      font-weight: 600;
      color: #374151;
    }

    .profil-container input {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border-radius: 6px;
      border: 1px solid #cbd5e1;
      box-sizing: border-box;
      background-color: #f9fafb;
    }

    .profil-container input[readonly] {
      color: #64748b;
      cursor: not-allowed;
    }

    .btn-password {
      display: inline-block;
      margin-top: 20px;
      text-align: center;
      background-color: #22c55e;
      padding: 10px 20px;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .btn-password:hover {
      background-color: #16a34a;
    }
  </style>
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../../../logo.png" class="logo-fixed" alt="Logo">
    </div>
    <div class="header-center">
      <h1>Profil Mahasiswa</h1>
      <p><?= htmlspecialchars($mahasiswa['nama']) ?> • NIM: <?= htmlspecialchars($mahasiswa['nim']) ?></p>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="../../dashboard_mahasiswa.php">🏠 Dashboard</a>
      <a href="../krs_saya.php">📄 KRS</a>
      <a href="../jadwal_saya.php">📅 Jadwal Kuliah</a>
      <a href="../nilai_saya.php">📝 Nilai</a>
      <a href="profile_mahasiswa.php" class="active">👤 Profil</a>
      <form action="../../../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="profil-container">
        <h2>Data Profil</h2>

        <label>Nama Lengkap:</label>
        <input type="text" value="<?= htmlspecialchars($mahasiswa['nama']) ?>" readonly>

        <label>NIM:</label>
        <input type="text" value="<?= htmlspecialchars($mahasiswa['nim']) ?>" readonly>

        <label>Jurusan:</label>
        <input type="text" value="<?= htmlspecialchars($mahasiswa['nama_jurusan']) ?>" readonly>

        <a href="ganti_password_mahasiswa.php" class="btn-password">🔐 Ganti Password</a>
      </div>
    </div>
  </div>
</body>
</html>
