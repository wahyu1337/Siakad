<?php
include '../koneksi.php';

$mahasiswa = mysqli_query($conn, "SELECT id, nim, nama FROM mahasiswa");
$matakuliah = mysqli_query($conn, "SELECT id, nama_mk FROM matakuliah");
$tahun_ajaran = mysqli_query($conn, "SELECT id, tahun_mulai, tahun_selesai, semester FROM tahun_ajaran");
$kelas = mysqli_query($conn, "SELECT id, nama_kelas FROM kelas");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mahasiswa_id = $_POST['mahasiswa_id'];
  $matakuliah_id = $_POST['matakuliah_id'];
  $tahun_ajaran_id = $_POST['tahun_ajaran_id'];
  $kelas_id = $_POST['kelas_id'];

  $simpan = mysqli_query($conn, "INSERT INTO krs (mahasiswa_id, matakuliah_id, tahun_ajaran_id, kelas_id) 
    VALUES ('$mahasiswa_id', '$matakuliah_id', '$tahun_ajaran_id', '$kelas_id')");

  if ($simpan) {
    header("Location: ../dashboard/data_krs.php");
    exit;
  } else {
    echo "<p style='color:red;text-align:center;'>Gagal menambahkan data KRS.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tambah KRS</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/style1.css">
</head>
<body>
<div class="page-center">
  <div class="container">
    <h2 class="form-title">Tambah Data KRS</h2>
    <form method="post">
      <label>Mahasiswa</label>
      <select name="mahasiswa_id" required>
        <option value="">-- Pilih --</option>
        <?php while ($m = mysqli_fetch_assoc($mahasiswa)) { ?>
          <option value="<?= $m['id'] ?>"><?= $m['nim'] ?> - <?= $m['nama'] ?></option>
        <?php } ?>
      </select>

      <label>Mata Kuliah</label>
      <select name="matakuliah_id" required>
        <option value="">-- Pilih --</option>
        <?php while ($mk = mysqli_fetch_assoc($matakuliah)) { ?>
          <option value="<?= $mk['id'] ?>"><?= $mk['nama_mk'] ?></option>
        <?php } ?>
      </select>

      <label>Tahun Ajaran</label>
      <select name="tahun_ajaran_id" required>
        <option value="">-- Pilih --</option>
        <?php while ($ta = mysqli_fetch_assoc($tahun_ajaran)) { ?>
          <option value="<?= $ta['id'] ?>">
            <?= $ta['tahun_mulai'] ?>/<?= $ta['tahun_selesai'] ?> - <?= $ta['semester'] ?>
          </option>
        <?php } ?>
      </select>

      <label>Kelas</label>
      <select name="kelas_id" required>
        <option value="">-- Pilih --</option>
        <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
          <option value="<?= $k['id'] ?>"><?= $k['nama_kelas'] ?></option>
        <?php } ?>
      </select>

      <button type="submit" class="button">Simpan</button>
      <a href="../dashboard/data_krs.php" class="button button-kembali">Kembali</a>
    </form>
  </div>
</div>
</body>
</html>
