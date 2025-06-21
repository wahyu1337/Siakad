<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
  header("Location: ../../index.php");
  exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$edit = false;
$data = ['matakuliah_id' => '', 'kelas_id' => '', 'tahun_ajaran_id' => ''];

if (isset($_GET['id'])) {
  $edit = true;
  $id = intval($_GET['id']);
  $result = mysqli_query($conn, "SELECT * FROM krs WHERE id = $id AND mahasiswa_id = $mahasiswa_id");
  if ($row = mysqli_fetch_assoc($result)) {
    $data = $row;
  } else {
    echo "<p style='color:red'>Data KRS tidak ditemukan atau bukan milik Anda.</p>";
    exit;
  }
}

if (isset($_POST['submit'])) {
  $matakuliah_id = intval($_POST['matakuliah_id']);
  $kelas_id = intval($_POST['kelas_id']);
  $tahun_ajaran_id = intval($_POST['tahun_ajaran_id']);

  if ($edit) {
    $id = intval($_POST['id']);
    $query = mysqli_query($conn, "UPDATE krs SET matakuliah_id=$matakuliah_id, kelas_id=$kelas_id, tahun_ajaran_id=$tahun_ajaran_id WHERE id=$id AND mahasiswa_id=$mahasiswa_id");
  } else {
    // Cek duplikat
    $cek = mysqli_query($conn, "SELECT * FROM krs WHERE mahasiswa_id = $mahasiswa_id AND matakuliah_id = $matakuliah_id AND tahun_ajaran_id = $tahun_ajaran_id");
    if (mysqli_num_rows($cek) > 0) {
      $error = "Anda sudah mengambil mata kuliah ini pada tahun ajaran yang sama.";
    } else {
      $query = mysqli_query($conn, "INSERT INTO krs (mahasiswa_id, matakuliah_id, kelas_id, tahun_ajaran_id) VALUES ($mahasiswa_id, $matakuliah_id, $kelas_id, $tahun_ajaran_id)");
    }
  }

  if (!isset($error)) {
    header("Location: krs_saya.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $edit ? "Edit" : "Isi" ?> KRS</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/layout.css">
  <style>
    .form-container {
      background: #fff;
      max-width: 600px;
      margin: 40px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }
    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .form-container select,
    .form-container button {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    .form-container button {
      background: #2563eb;
      color: #fff;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }
    .form-container button:hover {
      background: #1e40af;
    }
    .form-container .error {
      color: red;
      font-weight: bold;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../../logo.png" alt="Logo" class="logo-fixed">
    </div>
    <div class="header-center">
      <h1><?= $edit ? "Edit" : "Isi" ?> Kartu Rencana Studi (KRS)</h1>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="dashboard_mahasiswa.php">🏠 Dashboard</a>
      <a href="krs_saya.php" class="active">📄 KRS</a>
      <a href="jadwal_saya.php">📅 Jadwal</a>
      <a href="nilai_saya.php">📝 Nilai</a>
      <form action="../../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="form-container">
        <h2><?= $edit ? "Edit" : "Isi" ?> KRS</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="post">
          <?php if ($edit): ?>
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
          <?php endif; ?>

          <label for="matakuliah_id">Mata Kuliah</label>
          <select name="matakuliah_id" required>
            <option value="">-- Pilih Mata Kuliah --</option>
            <?php
            $matkul = mysqli_query($conn, "SELECT * FROM matakuliah ORDER BY nama_mk ASC");
            while ($row = mysqli_fetch_assoc($matkul)) {
              $sel = ($row['id'] == $data['matakuliah_id']) ? 'selected' : '';
              echo "<option value='{$row['id']}' $sel>{$row['nama_mk']}</option>";
            }
            ?>
          </select>

          <label for="kelas_id">Kelas</label>
          <select name="kelas_id" required>
            <option value="">-- Pilih Kelas --</option>
            <?php
            $kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
            while ($row = mysqli_fetch_assoc($kelas)) {
              $sel = ($row['id'] == $data['kelas_id']) ? 'selected' : '';
              echo "<option value='{$row['id']}' $sel>{$row['nama_kelas']}</option>";
            }
            ?>
          </select>

          <label for="tahun_ajaran_id">Tahun Ajaran</label>
          <select name="tahun_ajaran_id" required>
            <option value="">-- Pilih Tahun Ajaran --</option>
            <?php
            $ta = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY tahun_mulai DESC");
            while ($row = mysqli_fetch_assoc($ta)) {
              $label = "{$row['tahun_mulai']}/{$row['tahun_selesai']} ({$row['semester']})";
              $sel = ($row['id'] == $data['tahun_ajaran_id']) ? 'selected' : '';
              echo "<option value='{$row['id']}' $sel>$label</option>";
            }
            ?>
          </select>

          <button type="submit" name="submit"><?= $edit ? "Update" : "Simpan" ?></button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
