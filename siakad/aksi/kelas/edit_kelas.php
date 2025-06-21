<?php
include '../../koneksi.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kelas WHERE id = $id"));
$jurusan = mysqli_query($conn, "SELECT * FROM jurusan");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama_kelas'];
  $jurusan_id = $_POST['jurusan_id'];

  mysqli_query($conn, "UPDATE kelas SET nama_kelas='$nama', jurusan_id='$jurusan_id' WHERE id = $id");
  header("Location: ../../dashboard/data_kelas.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Kelas</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/style1.css">
</head>
<body>
<div class="page-center">
  <div class="container">
    <h2 class="form-title">Form Edit Kelas</h2>
    <form method="post">
      <label>Nama Kelas</label>
      <input type="text" name="nama_kelas" value="<?= $data['nama_kelas'] ?>" required>

      <label>Jurusan</label>
      <select name="jurusan_id" required>
        <?php while ($j = mysqli_fetch_assoc($jurusan)) { ?>
          <option value="<?= $j['id'] ?>" <?= $data['jurusan_id'] == $j['id'] ? 'selected' : '' ?>>
            <?= $j['nama_jurusan'] ?>
          </option>
        <?php } ?>
      </select>

      <button type="submit" class="button">Simpan</button>
      <a href="../../dashboard/data_kelas.php" class="button button-kembali">Batal</a>
    </form>
  </div>
</div>
</body>
</html>
