<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nip = $_POST['nip'];
  $nama = $_POST['nama'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $email = $_POST['email'];
  $bidang = $_POST['bidang'];

  $query = mysqli_query($conn, "INSERT INTO dosen (nip, nama, jenis_kelamin, email, bidang) 
                                VALUES ('$nip', '$nama', '$jenis_kelamin', '$email', '$bidang')");

  if ($query) {
    header("Location: ../dashboard/data_dosen.php");
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
  <title>Tambah Dosen</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/style1.css">
</head>
<body>
  <div class="page-center">
    <div class="container">
      <h2 class="form-title">Form Tambah Dosen</h2>
      <form method="post">
        <label>NIP</label>
        <input type="text" name="nip" required>

        <label>Nama Dosen</label>
        <input type="text" name="nama" required>

        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" required>
          <option value="">-- Pilih --</option>
          <option value="Laki-laki">Laki-laki</option>
          <option value="Perempuan">Perempuan</option>
        </select>

        <label>Email (opsional)</label>
        <input type="email" name="email">

        <label>Bidang (opsional)</label>
        <input type="text" name="bidang">

        <button type="submit" class="button">Simpan</button>
        <a href="../dashboard/data_dosen.php" class="button button-kembali">Kembali</a>
      </form>
    </div>
  </div>
</body>
</html>
