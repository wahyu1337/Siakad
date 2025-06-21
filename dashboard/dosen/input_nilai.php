<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
  header("Location: ../../index.php");
  exit;
}

$dosen_id = intval($_SESSION['dosen_id']);
$success = $error = "";

// Ambil daftar jadwal yang diajar dosen
$jadwal_result = mysqli_query($conn, "
  SELECT j.id AS jadwal_id, mk.nama_mk, k.nama_kelas 
  FROM jadwal j
  JOIN matakuliah mk ON j.matakuliah_id = mk.id
  JOIN kelas k ON j.kelas_id = k.id
  WHERE j.dosen_id = $dosen_id
");

// Proses input nilai
if (isset($_POST['submit_nilai'])) {
  $jadwal_id = intval($_POST['jadwal_id']);
  $mahasiswa_id = intval($_POST['mahasiswa_id']);
  $nilai = intval($_POST['nilai']);

  if ($jadwal_id && $mahasiswa_id) {
    // Cek kepemilikan jadwal
    $cek_jadwal = mysqli_query($conn, "SELECT * FROM jadwal WHERE id = $jadwal_id AND dosen_id = $dosen_id");
    if (mysqli_num_rows($cek_jadwal) === 0) {
      $error = "Jadwal tidak valid atau bukan milik Anda.";
    } else {
      $jadwal = mysqli_fetch_assoc($cek_jadwal);
      $matakuliah_id = $jadwal['matakuliah_id'];

      // Cek apakah nilai sudah diinput
      $cek_nilai = mysqli_query($conn, "SELECT id FROM nilai WHERE mahasiswa_id = $mahasiswa_id AND matakuliah_id = $matakuliah_id");
      if (mysqli_num_rows($cek_nilai) > 0) {
        $error = "Nilai sudah ada untuk mahasiswa ini.";
      } else {
        $simpan = mysqli_query($conn, "INSERT INTO nilai (mahasiswa_id, matakuliah_id, nilai_angka) VALUES ($mahasiswa_id, $matakuliah_id, $nilai)");
        $success = $simpan ? "Nilai berhasil disimpan." : "Gagal menyimpan nilai.";
      }
    }
  } else {
    $error = "Semua data harus diisi.";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Nilai</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/layout.css">
  <link rel="stylesheet" href="../../css/dashboard.css">
  <style>
    .form-container {
      background: #fff;
      max-width: 600px;
      margin: 30px auto;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .form-container select,
    .form-container input {
      width: 100%;
      padding: 10px;
      margin: 10px 0 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    .form-container button {
      width: 100%;
      padding: 12px;
      background: #2563eb;
      color: #fff;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    .form-container .message {
      text-align: center;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../../logo.png" alt="Logo" class="logo-fixed">
    </div>
    <div class="header-center">
      <h1>Input Nilai Mahasiswa</h1>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="../dashboard_dosen.php">🏠 Dashboard</a>
      <a href="jadwal_saya.php">📅 Jadwal Saya</a>
      <a href="input_nilai.php" class="active">📝 Input Nilai</a>
      <a href="mahasiswa_perkelas.php">👨‍🎓 Mahasiswa Perkelas</a>
      <form action="../../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="form-container">
        <h2>Input Nilai</h2>
        <?php if ($success) echo "<p class='message' style='color:green;'>$success</p>"; ?>
        <?php if ($error) echo "<p class='message' style='color:red;'>$error</p>"; ?>

        <form method="post">
          <label for="jadwal_id">Pilih Jadwal:</label>
          <select name="jadwal_id" id="jadwal_id" onchange="this.form.submit()" required>
            <option value="">-- Pilih Jadwal --</option>
            <?php
            mysqli_data_seek($jadwal_result, 0);
            while ($row = mysqli_fetch_assoc($jadwal_result)) {
              $selected = (isset($_POST['jadwal_id']) && $_POST['jadwal_id'] == $row['jadwal_id']) ? 'selected' : '';
              echo "<option value='{$row['jadwal_id']}' $selected>{$row['nama_mk']} ({$row['nama_kelas']})</option>";
            }
            ?>
          </select>
        </form>

        <?php
        if (isset($_POST['jadwal_id']) && $_POST['jadwal_id'] != "") {
          $jadwal_id = intval($_POST['jadwal_id']);
          $kelas_result = mysqli_query($conn, "SELECT kelas_id FROM jadwal WHERE id = $jadwal_id");
          $kelas_data = mysqli_fetch_assoc($kelas_result);
          $kelas_id = $kelas_data['kelas_id'];

          $mhs_result = mysqli_query($conn, "SELECT id, nim, nama FROM mahasiswa WHERE kelas_id = $kelas_id");
          if (mysqli_num_rows($mhs_result) > 0) {
        ?>
        <form method="post">
          <input type="hidden" name="jadwal_id" value="<?= $jadwal_id ?>">

          <label for="mahasiswa_id">Pilih Mahasiswa:</label>
          <select name="mahasiswa_id" id="mahasiswa_id" required>
            <option value="">-- Pilih Mahasiswa --</option>
            <?php while ($mhs = mysqli_fetch_assoc($mhs_result)) {
              echo "<option value='{$mhs['id']}'>{$mhs['nim']} - {$mhs['nama']}</option>";
            } ?>
          </select>

          <label for="nilai">Nilai Angka:</label>
          <input type="number" name="nilai" id="nilai" min="0" max="100" required>

          <button type="submit" name="submit_nilai">Simpan Nilai</button>
        </form>
        <?php
          } else {
            echo "<p class='message'>Tidak ada mahasiswa di kelas ini.</p>";
          }
        }
        ?>
      </div>
    </div>
  </div>
</body>
</html>
