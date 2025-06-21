<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama_jurusan = $_POST['nama_jurusan'];

  $query = mysqli_query($conn, "INSERT INTO jurusan (nama_jurusan) VALUES ('$nama_jurusan')");

  if ($query) {
    header("Location: ../dashboard/data_jurusan.php");
    exit;
  } else {
    echo "<p style='color:red;text-align:center;'>Gagal menyimpan data!</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Jurusan</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/style1.css">
</head>
<body>
  <div class="page-center">
    <div class="container">
      <h2 class="form-title">Form Tambah Jurusan</h2>
      <form method="post">
        <label>Nama Jurusan</label>
        <input type="text" name="nama_jurusan" required>

        <button type="submit" class="button">Simpan</button>
        <a href="../dashboard/data_jurusan.php" class="button button-kembali">Kembali</a>
      </form>
    </div>
  </div>
</body>
</html>
