<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../../index.php");
  exit;
}

if (!isset($_GET['id'])) {
  echo "ID mata kuliah tidak ditemukan.";
  exit;
}

$id = intval($_GET['id']);

// Ambil data matakuliah
$query = mysqli_query($conn, "SELECT * FROM matakuliah WHERE id = $id");
$mk = mysqli_fetch_assoc($query);

if (!$mk) {
  echo "Data tidak ditemukan.";
  exit;
}

// Ambil semua jurusan
$jurusan_result = mysqli_query($conn, "SELECT id, nama_jurusan FROM jurusan");

// Proses edit
if (isset($_POST['submit'])) {
  $nama_mk = mysqli_real_escape_string($conn, $_POST['nama_mk']);
  $semester = intval($_POST['semester']);
  $jurusan_id = intval($_POST['jurusan_id']);

  $update = mysqli_query($conn, "
    UPDATE matakuliah 
    SET nama_mk = '$nama_mk', semester = $semester, jurusan_id = $jurusan_id 
    WHERE id = $id
  ");

  if ($update) {
    header("Location: ../../dashboard/data_matakuliah.php");
    exit;
  } else {
    $error = "Gagal mengupdate data.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Mata Kuliah</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/style1.css">
</head>
<body>
  <div class="page-center">
    <div class="container">
      <h2 class="form-title">Edit Mata Kuliah</h2>
      <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

      <form method="post">
        <label>Nama Mata Kuliah</label>
        <input type="text" name="nama_mk" value="<?= htmlspecialchars($mk['nama_mk']) ?>" required>

        <label>Semester</label>
        <select name="semester" required>
          <option value="">-- Pilih Semester --</option>
          <?php for ($i = 1; $i <= 8; $i++): ?>
            <option value="<?= $i ?>" <?= $mk['semester'] == $i ? 'selected' : '' ?>>Semester <?= $i ?></option>
          <?php endfor; ?>
        </select>

        <label>Jurusan</label>
        <select name="jurusan_id" required>
          <option value="">-- Pilih Jurusan --</option>
          <?php while ($row = mysqli_fetch_assoc($jurusan_result)): ?>
            <option value="<?= $row['id'] ?>" <?= $row['id'] == $mk['jurusan_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['nama_jurusan']) ?>
            </option>
          <?php endwhile; ?>
        </select>

        <button type="submit" name="submit" class="button">Simpan Perubahan</button>
        <a href="../../dashboard/data_matakuliah.php" class="button button-kembali">Kembali</a>
      </form>
    </div>
  </div>
</body>
</html>
