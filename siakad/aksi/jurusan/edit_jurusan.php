<?php
include '../../koneksi.php';

$id = $_GET['id'];
$jurusan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jurusan WHERE id = $id"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama_jurusan = $_POST['nama_jurusan'];

  mysqli_query($conn, "UPDATE jurusan SET nama_jurusan = '$nama_jurusan' WHERE id = $id");
  header("Location: ../../dashboard/data_jurusan.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Jurusan</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/style1.css">
</head>
<body>
  <div class="page-center">
    <div class="container">
      <h2 class="form-title">Form Edit Jurusan</h2>
      <form method="post">
        <label>Nama Jurusan</label>
        <input type="text" name="nama_jurusan" value="<?= $jurusan['nama_jurusan'] ?>" required>

        <button type="submit" class="button">Simpan Perubahan</button>
        <a href="../../dashboard/data_jurusan.php" class="button button-kembali">Kembali</a>
      </form>
    </div>
  </div>
</body>
</html>
