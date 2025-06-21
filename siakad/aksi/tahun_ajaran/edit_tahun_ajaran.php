<?php
include '../../koneksi.php';
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE id = $id"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tahun_mulai = $_POST['tahun_mulai'];
  $tahun_selesai = $_POST['tahun_selesai'];
  $semester = $_POST['semester'];
  $status_aktif = isset($_POST['status_aktif']) ? 1 : 0;

  mysqli_query($conn, "UPDATE tahun_ajaran SET 
    tahun_mulai='$tahun_mulai', 
    tahun_selesai='$tahun_selesai', 
    semester='$semester',
    status_aktif='$status_aktif'
    WHERE id=$id");

  header("Location: ../../dashboard/data_tahun_ajaran.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Tahun Ajaran</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/style1.css">
</head>
<body>
<div class="page-center">
  <div class="container">
    <h2 class="form-title">Edit Tahun Ajaran</h2>
    <form method="post">
      <label>Tahun Mulai</label>
      <input type="number" name="tahun_mulai" value="<?= $data['tahun_mulai'] ?>" required>

      <label>Tahun Selesai</label>
      <input type="number" name="tahun_selesai" value="<?= $data['tahun_selesai'] ?>" required>

      <label>Semester</label>
      <select name="semester" required>
        <option value="Ganjil" <?= $data['semester'] == 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
        <option value="Genap" <?= $data['semester'] == 'Genap' ? 'selected' : '' ?>>Genap</option>
      </select>

      <label><input type="checkbox" name="status_aktif" value="1" <?= $data['status_aktif'] ? 'checked' : '' ?>> Aktif</label>

      <button type="submit" class="button">Update</button>
      <a href="../../dashboard/data_tahun_ajaran.php" class="button button-kembali">Batal</a>
    </form>
  </div>
</div>
</body>
</html>
