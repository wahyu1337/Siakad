<?php
session_start();
include '../../koneksi.php';

// Cek login & role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
  header("Location: ../../index.php");
  exit;
}

$dosen_id = $_SESSION['dosen_id'];

$kelas_result = mysqli_query($conn, "
  SELECT DISTINCT k.id, k.nama_kelas 
  FROM jadwal j
  JOIN kelas k ON j.kelas_id = k.id
  WHERE j.dosen_id = $dosen_id
  ORDER BY k.nama_kelas
");

$kelas_terpilih = $_GET['kelas_id'] ?? null;
$jumlah_mahasiswa = 0;
$mahasiswa_result = null;

if ($kelas_terpilih) {
  $mahasiswa_result = mysqli_query($conn, "
    SELECT nim, nama FROM mahasiswa WHERE kelas_id = $kelas_terpilih
  ");
  $jumlah_mahasiswa = mysqli_num_rows($mahasiswa_result);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Mahasiswa Perkelas</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/layout.css">
  <link rel="stylesheet" href="../../css/dashboard.css">
  <style>
    .select-wrapper {
      max-width: 400px;
      margin-bottom: 20px;
    }

    select {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .badge {
      display: inline-block;
      padding: 6px 12px;
      background-color: #2563eb;
      color: white;
      font-weight: bold;
      border-radius: 20px;
      margin-left: 10px;
      font-size: 13px;
    }

    @media (max-width: 600px) {
      .form-title {
        font-size: 18px;
      }
      .select-wrapper {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../../logo.png" alt="Logo" class="logo-fixed">
    </div>
    <div class="header-center">
      <h1>Mahasiswa Perkelas</h1>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="../dashboard_dosen.php">🏠 Dashboard</a>
      <a href="jadwal_saya.php">📅 Jadwal Saya</a>
      <a href="input_nilai.php">📝 Input Nilai</a>
      <a href="mahasiswa_perkelas.php" class="active">👨‍🎓 Mahasiswa Perkelas</a>
      <form action="../../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Lihat Daftar Mahasiswa Berdasarkan Kelas</h2>

        <form method="get" class="select-wrapper">
          <select name="kelas_id" onchange="this.form.submit()" required>
            <option value="">-- Pilih Kelas --</option>
            <?php while ($k = mysqli_fetch_assoc($kelas_result)) {
              $selected = ($kelas_terpilih == $k['id']) ? 'selected' : '';
              echo "<option value='{$k['id']}' $selected>{$k['nama_kelas']}</option>";
            } ?>
          </select>
        </form>

        <?php if ($kelas_terpilih): ?>
          <h3 style="margin-bottom: 10px;">
            Daftar Mahasiswa
            <span class="badge"><?= $jumlah_mahasiswa ?> Orang</span>
          </h3>
          <table>
            <thead>
              <tr>
                <th>NIM</th>
                <th>Nama</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($jumlah_mahasiswa > 0): ?>
                <?php while ($mhs = mysqli_fetch_assoc($mahasiswa_result)) { ?>
                  <tr>
                    <td><?= htmlspecialchars($mhs['nim']) ?></td>
                    <td><?= htmlspecialchars($mhs['nama']) ?></td>
                  </tr>
                <?php } ?>
              <?php else: ?>
                <tr><td colspan="2">Tidak ada mahasiswa di kelas ini.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
