<?php
include '../../koneksi.php';
$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM nilai WHERE id = $id"));
$mahasiswa = mysqli_query($conn, "SELECT id, nama FROM mahasiswa");
$matakuliah = mysqli_query($conn, "SELECT id, nama_mk FROM matakuliah");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mahasiswa_id = $_POST['mahasiswa_id'];
  $matakuliah_id = $_POST['matakuliah_id'];
  $nilai_angka = $_POST['nilai_angka'];
  $semester = $_POST['semester'];

  mysqli_query($conn, "UPDATE nilai SET 
    mahasiswa_id='$mahasiswa_id', 
    matakuliah_id='$matakuliah_id', 
    nilai_angka='$nilai_angka',
    semester='$semester'
    WHERE id=$id");

  header("Location: ../../dashboard/data_nilai.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Nilai</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/style1.css">
</head>
<body>
<div class="page-center">
  <div class="container">
    <h2 class="form-title">Edit Nilai Mahasiswa</h2>
    <form method="post">
      <label>Mahasiswa</label>
      <select name="mahasiswa_id" required>
        <?php while ($m = mysqli_fetch_assoc($mahasiswa)) { ?>
          <option value="<?= $m['id'] ?>" <?= $m['id'] == $data['mahasiswa_id'] ? 'selected' : '' ?>>
            <?= $m['nama'] ?>
          </option>
        <?php } ?>
      </select>

      <label>Mata Kuliah</label>
      <select name="matakuliah_id" required>
        <?php while ($mk = mysqli_fetch_assoc($matakuliah)) { ?>
          <option value="<?= $mk['id'] ?>" <?= $mk['id'] == $data['matakuliah_id'] ? 'selected' : '' ?>>
            <?= $mk['nama_mk'] ?>
          </option>
        <?php } ?>
      </select>

      <label>Nilai Angka</label>
      <input type="number" name="nilai_angka" value="<?= $data['nilai_angka'] ?>" required>

      <label>Semester</label>
      <input type="text" name="semester" value="<?= $data['semester'] ?>" required>

      <button type="submit" class="button">Update</button>
      <a href="../../dashboard/data_nilai.php" class="button button-kembali">Batal</a>
    </form>
  </div>
</div>
</body>
</html>
