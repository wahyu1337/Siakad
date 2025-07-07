<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
  header("Location: ../../index.php");
  exit;
}

$dosen_id = $_SESSION['dosen_id'] ?? null;
if (!$dosen_id) {
  echo "<p style='color:red;text-align:center;'>Dosen ID tidak ditemukan di session!</p>";
  exit;
}

// Ambil info dosen
$result = mysqli_query($conn, "SELECT nama, email FROM dosen WHERE id = $dosen_id");
$dosen = mysqli_fetch_assoc($result);

// Filter
$filter_hari = $_GET['hari'] ?? '';
$filter_kelas = $_GET['kelas'] ?? '';

// List kelas
$kelas_result = mysqli_query($conn, "
  SELECT DISTINCT k.id, k.nama_kelas 
  FROM jadwal j 
  JOIN kelas k ON j.kelas_id = k.id 
  WHERE j.dosen_id = $dosen_id
");

// Jadwal
$where = "WHERE j.dosen_id = $dosen_id";
if ($filter_hari) $where .= " AND j.hari = '$filter_hari'";
if ($filter_kelas) $where .= " AND j.kelas_id = '$filter_kelas'";

$jadwal = mysqli_query($conn, "
  SELECT j.hari, j.jam_mulai, j.jam_selesai, mk.nama_mk, k.nama_kelas 
  FROM jadwal j 
  JOIN matakuliah mk ON j.matakuliah_id = mk.id 
  JOIN kelas k ON j.kelas_id = k.id 
  $where 
  ORDER BY FIELD(j.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), j.jam_mulai
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Jadwal Saya</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/layout.css">
  <style>
    .sidebar-user {
      color: white;
      text-align: center;
      padding: 15px;
      background-color:#4f46e5;
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    .sidebar-user strong {
      font-size: 1.1em;
      display: block;
    }
    .sidebar-user small {
      color: #ccc;
      font-size: 0.9em;
    }
    .filter-box {
      background-color: #f8fafc;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 10px;
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      align-items: center;
    }
    .filter-box select, .filter-box button {
      padding: 10px 14px;
      border-radius: 6px;
      border: 1px solid #cbd5e1;
      font-size: 14px;
    }
    .filter-box button {
      background-color: #2563eb;
      color: white;
      border: none;
      cursor: pointer;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 10px;
      border: 1px solid #e2e8f0;
      text-align: center;
    }
    th {
      background-color: #f1f5f9;
    }
  </style>
</head>
<body>
<header>
  <div class="header-left">
    <img src="../../logo.png" class="logo-fixed" alt="Logo">
  </div>
  <div class="header-center">
    <h1>Jadwal Mengajar</h1>
  </div>
</header>

<div class="main-content">
  <div class="sidebar">
    <div class="sidebar-user">
      <strong><?= htmlspecialchars($dosen['nama']) ?></strong>
      <small><?= htmlspecialchars($dosen['email']) ?></small>
    </div>
    <a href="../dashboard_dosen.php">🏠 Dashboard</a>
    <a href="jadwal_saya.php" class="active">📅 Jadwal Saya</a>
    <a href="input_nilai.php">📝 Input Nilai</a>
    <a href="profil/profile_dosen.php">👤 Profil</a>
    <form action="../../logout.php" method="post">
      <button type="submit" class="logout-button">🔓 Logout</button>
    </form>
  </div>

  <div class="page-wrapper">
    <div class="container">
    <form method="get" class="filter-box">
        <select name="hari" onchange="this.form.submit()">
          <option value="">Semua Hari</option>
          <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari): ?>
            <option value="<?= $hari ?>" <?= ($filter_hari == $hari ? 'selected' : '') ?>><?= $hari ?></option>
          <?php endforeach; ?>
        </select>

        <select name="kelas" onchange="this.form.submit()">
          <option value="">Semua Kelas</option>
          <?php while ($k = mysqli_fetch_assoc($kelas_result)): ?>
            <option value="<?= $k['id'] ?>" <?= ($filter_kelas == $k['id'] ? 'selected' : '') ?>><?= $k['nama_kelas'] ?></option>
          <?php endwhile; ?>
        </select>
      </form>
      <h2 class="form-title">Jadwal Saya</h2>
      <table>
        <thead>
          <tr>
            <th>Hari</th>
            <th>Jam</th>
            <th>Mata Kuliah</th>
            <th>Kelas</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($jadwal) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($jadwal)): ?>
              <tr>
                <td><?= $row['hari'] ?></td>
                <td><?= substr($row['jam_mulai'], 0, 5) ?> - <?= substr($row['jam_selesai'], 0, 5) ?></td>
                <td><?= $row['nama_mk'] ?></td>
                <td><?= $row['nama_kelas'] ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4">Tidak ada jadwal ditemukan.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>