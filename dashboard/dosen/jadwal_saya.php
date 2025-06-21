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

// Ambil filter
$filter_hari = $_GET['hari'] ?? '';
$filter_kelas = $_GET['kelas'] ?? '';

// List kelas untuk filter
$kelas_result = mysqli_query($conn, "
  SELECT DISTINCT k.id, k.nama_kelas 
  FROM jadwal j 
  JOIN kelas k ON j.kelas_id = k.id 
  WHERE j.dosen_id = $dosen_id
");

// Query jadwal
$where = "WHERE j.dosen_id = $dosen_id";
if ($filter_hari) $where .= " AND j.hari = '$filter_hari'";
if ($filter_kelas) $where .= " AND j.kelas_id = '$filter_kelas'";

$jadwal = mysqli_query($conn, "
  SELECT j.hari, j.jam_mulai, j.jam_selesai, mk.nama_mk, k.nama_kelas 
  FROM jadwal j 
  JOIN matakuliah mk ON j.matakuliah_id = mk.id 
  JOIN kelas k ON j.kelas_id = k.id 
  $where 
  ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), j.jam_mulai
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Jadwal Mengajar Saya</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/layout.css">
  <style>
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
      transition: 0.3s;
    }
    .filter-box button:hover {
      background-color: #1e40af;
    }

    @media screen and (max-width: 600px) {
      .filter-box {
        flex-direction: column;
        align-items: stretch;
      }
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table th, table td {
      padding: 12px;
      border: 1px solid #e2e8f0;
      text-align: center;
      font-size: 14px;
    }

    table th {
      background-color: #f1f5f9;
    }

    table tbody tr:hover {
      background-color: #f0fdfa;
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
      <p>Dosen: <?= $_SESSION['username'] ?></p>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="../dashboard_dosen.php">🏠 Dashboard</a>
      <a href="jadwal_saya.php" class="active">📅 Jadwal Saya</a>
      <a href="input_nilai.php">📝 Input Nilai</a>
      <a href="mahasiswa_perkelas.php">👨‍🎓 Mahasiswa Perkelas</a>
      <form action="../../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Filter Jadwal</h2>
        <form method="get" class="filter-box">
          <select name="hari">
            <option value="">Semua Hari</option>
            <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari): ?>
              <option value="<?= $hari ?>" <?= $filter_hari == $hari ? 'selected' : '' ?>><?= $hari ?></option>
            <?php endforeach; ?>
          </select>

          <select name="kelas">
            <option value="">Semua Kelas</option>
            <?php while ($k = mysqli_fetch_assoc($kelas_result)): ?>
              <option value="<?= $k['id'] ?>" <?= $filter_kelas == $k['id'] ? 'selected' : '' ?>>
                <?= $k['nama_kelas'] ?>
              </option>
            <?php endwhile; ?>
          </select>

          <button type="submit">Tampilkan</button>
        </form>

        <h2 class="form-title">Jadwal Mengajar Saya</h2>
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
              <?php while ($j = mysqli_fetch_assoc($jadwal)): ?>
                <tr>
                  <td><?= $j['hari'] ?></td>
                  <td><?= date("H:i", strtotime($j['jam_mulai'])) ?> - <?= date("H:i", strtotime($j['jam_selesai'])) ?></td>
                  <td><?= $j['nama_mk'] ?></td>
                  <td><?= $j['nama_kelas'] ?></td>
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
