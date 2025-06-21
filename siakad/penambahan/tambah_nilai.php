<?php
include '../koneksi.php';

$mahasiswa = mysqli_query($conn, "
  SELECT m.id, m.nim, m.nama, j.nama_jurusan 
  FROM mahasiswa m 
  JOIN jurusan j ON m.jurusan_id = j.id
");
$matakuliah = mysqli_query($conn, "SELECT * FROM matakuliah");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mahasiswa_id = $_POST['mahasiswa_id'];
  $matakuliah_id = $_POST['matakuliah_id'];
  $nilai_angka = $_POST['nilai_angka'];
  $semester = $_POST['semester'];

  $simpan = mysqli_query($conn, "INSERT INTO nilai (mahasiswa_id, matakuliah_id, nilai_angka, semester)
    VALUES ('$mahasiswa_id', '$matakuliah_id', '$nilai_angka', '$semester')");

  if ($simpan) {
    header("Location: ../dashboard/data_nilai.php");
    exit;
  } else {
    echo "<p style='color:red;text-align:center;'>Gagal menyimpan nilai.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tambah Nilai</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/style1.css">
</head>
<body>
<div class="page-center">
  <div class="container">
    <h2 class="form-title">Tambah Nilai Mahasiswa</h2>
    <form method="post">
      <label>Mahasiswa</label>
      <select name="mahasiswa_id" required>
        <option value="">-- Pilih --</option>
        <?php while ($m = mysqli_fetch_assoc($mahasiswa)) { ?>
          <option value="<?= $m['id'] ?>"><?= $m['nim'] ?> - <?= $m['nama'] ?> (<?= $m['nama_jurusan'] ?>)</option>
        <?php } ?>
      </select>

      <label>Mata Kuliah</label>
      <select name="matakuliah_id" required>
        <option value="">-- Pilih --</option>
        <?php while ($mk = mysqli_fetch_assoc($matakuliah)) { ?>
          <option value="<?= $mk['id'] ?>"><?= $mk['nama_mk'] ?></option>
        <?php } ?>
      </select>

      <label>Nilai Angka</label>
      <input type="number" name="nilai_angka" min="0" max="100" required>

      <label>Semester</label>
      <input type="text" name="semester" placeholder="Misal: Ganjil/Genap atau 1/2/3..." required>

      <button type="submit" class="button">Simpan</button>
      <a href="../dashboard/data_nilai.php" class="button button-kembali">Kembali</a>
    </form>
  </div>
</div>
</body>
</html>
