<?php
include '../../koneksi.php';
$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM krs WHERE id = $id"));
$mahasiswa = mysqli_query($conn, "SELECT id, nama FROM mahasiswa");
$matakuliah = mysqli_query($conn, "SELECT id, nama_mk FROM matakuliah");
$tahun_ajaran = mysqli_query($conn, "SELECT id, tahun_mulai, tahun_selesai, semester FROM tahun_ajaran");
$kelas = mysqli_query($conn, "SELECT id, nama_kelas FROM kelas");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mahasiswa_id = $_POST['mahasiswa_id'];
  $matakuliah_id = $_POST['matakuliah_id'];
  $tahun_ajaran_id = $_POST['tahun_ajaran_id'];
  $kelas_id = $_POST['kelas_id'];

  mysqli_query($conn, "UPDATE krs SET 
    mahasiswa_id = '$mahasiswa_id',
    matakuliah_id = '$matakuliah_id',
    tahun_ajaran_id = '$tahun_ajaran_id',
    kelas_id = '$kelas_id'
    WHERE id = $id");

  header("Location: ../../dashboard/data_krs.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit KRS</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/style1.css">
</head>
<body>
<div class="page-center">
  <div class="container">
    <h2 class="form-title">Edit Data KRS</h2>
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

      <label>Tahun Ajaran</label>
      <select name="tahun_ajaran_id" required>
        <?php while ($ta = mysqli_fetch_assoc($tahun_ajaran)) { ?>
          <option value="<?= $ta['id'] ?>" <?= $ta['id'] == $data['tahun_ajaran_id'] ? 'selected' : '' ?>>
            <?= $ta['tahun_mulai'] ?>/<?= $ta['tahun_selesai'] ?> - <?= $ta['semester'] ?>
          </option>
        <?php } ?>
      </select>

      <label>Kelas</label>
      <select name="kelas_id" required>
        <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
          <option value="<?= $k['id'] ?>" <?= $k['id'] == $data['kelas_id'] ? 'selected' : '' ?>>
            <?= $k['nama_kelas'] ?>
          </option>
        <?php } ?>
      </select>

      <button type="submit" class="button">Update</button>
      <a href="../../dashboard/data_krs.php" class="button button-kembali">Batal</a>
    </form>
  </div>
</div>
</body>
</html>
