<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tahun_mulai = $_POST['tahun_mulai'];
  $tahun_selesai = $_POST['tahun_selesai'];
  $semester = $_POST['semester'];
  $status_aktif = isset($_POST['status_aktif']) ? 1 : 0;

  $simpan = mysqli_query($conn, "INSERT INTO tahun_ajaran (tahun_mulai, tahun_selesai, semester, status_aktif)
              VALUES ('$tahun_mulai', '$tahun_selesai', '$semester', '$status_aktif')");

  if ($simpan) {
    header("Location: ../dashboard/data_tahun_ajaran.php");
    exit;
  } else {
    echo "<p style='color:red;text-align:center;'>Gagal menambahkan tahun ajaran.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tambah Tahun Ajaran</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/style1.css">

</head>
<body>
  <div class="page-center">
    <div class="container">
      <h2 class="form-title">Tambah Tahun Ajaran</h2>
      <form method="post">
        <label>Tahun Mulai</label>
        <input type="number" name="tahun_mulai" required>

        <label>Tahun Selesai</label>
        <input type="number" name="tahun_selesai" required>

        <label>Semester</label>
        <select name="semester" required>
          <option value="Ganjil">Ganjil</option>
          <option value="Genap">Genap</option>
        </select>

        <label><input type="checkbox" name="status_aktif" value="1"> Aktif</label>

        <button type="submit" class="button">Simpan</button>
        <a href="../dashboard/data_tahun_ajaran.php" class="button button-kembali">Kembali</a>
      </form>
    </div>
  </div>
</body>
</html>
