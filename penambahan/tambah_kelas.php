<?php
include '../koneksi.php';
$jurusan = mysqli_query($conn, "SELECT * FROM jurusan");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama_kelas'];
  $jurusan_id = $_POST['jurusan_id'];

  $query = mysqli_query($conn, "INSERT INTO kelas (nama_kelas, jurusan_id) VALUES ('$nama', '$jurusan_id')");
  if ($query) {
    header("Location: ../dashboard/data_kelas.php");
    exit;
  } else {
    echo "<p style='color:red;text-align:center;'>Gagal menyimpan data kelas.</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Kelas</title>
  <link rel="stylesheet" href="../css/style1.css">
</head>
<body>
<div class="page-center">
  <div class="container">
    <h2 class="form-title">Form Tambah Kelas</h2>
    <form method="post">
      <label>Nama Kelas</label>
      <input type="text" name="nama_kelas" required>

      <label>Jurusan</label>
      <select name="jurusan_id" required>
        <option value="">-- Pilih Jurusan --</option>
        <?php while ($j = mysqli_fetch_assoc($jurusan)) { ?>
          <option value="<?= $j['id'] ?>"><?= $j['nama_jurusan'] ?></option>
        <?php } ?>
      </select>

      <button type="submit" class="button">Simpan</button>
      <a href="../dashboard/data_kelas.php" class="button button-kembali">Kembali</a>
    </form>
  </div>
</div>
</body>
</html>
