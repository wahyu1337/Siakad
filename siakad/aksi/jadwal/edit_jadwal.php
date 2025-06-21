<?php
include '../../koneksi.php';
$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jadwal WHERE id = $id"));
$matakuliah = mysqli_query($conn, "SELECT * FROM matakuliah");
$dosen = mysqli_query($conn, "SELECT * FROM dosen");
$kelas = mysqli_query($conn, "SELECT * FROM kelas");
$tahun_ajaran = mysqli_query($conn, "SELECT * FROM tahun_ajaran");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $matakuliah_id = $_POST['matakuliah_id'];
  $dosen_id = $_POST['dosen_id'];
  $kelas_id = $_POST['kelas_id'];
  $hari = $_POST['hari'];
  $jam_mulai = $_POST['jam_mulai'];
  $jam_selesai = $_POST['jam_selesai'];
  $tahun_ajaran_id = $_POST['tahun_ajaran_id'];

  mysqli_query($conn, "UPDATE jadwal SET 
    matakuliah_id='$matakuliah_id',
    dosen_id='$dosen_id',
    kelas_id='$kelas_id',
    hari='$hari',
    jam_mulai='$jam_mulai',
    jam_selesai='$jam_selesai',
    tahun_ajaran_id='$tahun_ajaran_id'
    WHERE id = $id");

  header("Location: ../../dashboard/data_jadwal.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Jadwal</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/style1.css">
</head>
<body>
<div class="page-center">
  <div class="container">
    <h2 class="form-title">Edit Jadwal</h2>
    <form method="post">
      <label>Mata Kuliah</label>
      <select name="matakuliah_id" required>
        <?php while ($m = mysqli_fetch_assoc($matakuliah)) { ?>
          <option value="<?= $m['id'] ?>" <?= $m['id'] == $data['matakuliah_id'] ? 'selected' : '' ?>>
            <?= $m['nama_mk'] ?>
          </option>
        <?php } ?>
      </select>

      <label>Dosen</label>
      <select name="dosen_id" required>
        <?php while ($d = mysqli_fetch_assoc($dosen)) { ?>
          <option value="<?= $d['id'] ?>" <?= $d['id'] == $data['dosen_id'] ? 'selected' : '' ?>>
            <?= $d['nama'] ?>
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

      <label>Hari</label>
      <select name="hari" required>
        <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h) { ?>
          <option value="<?= $h ?>" <?= $data['hari'] == $h ? 'selected' : '' ?>><?= $h ?></option>
        <?php } ?>
      </select>

      <label>Jam Mulai</label>
      <input type="time" name="jam_mulai" value="<?= $data['jam_mulai'] ?>" required>

      <label>Jam Selesai</label>
      <input type="time" name="jam_selesai" value="<?= $data['jam_selesai'] ?>" required>

      <label>Tahun Ajaran</label>
      <select name="tahun_ajaran_id" required>
        <?php while ($ta = mysqli_fetch_assoc($tahun_ajaran)) { ?>
          <option value="<?= $ta['id'] ?>" <?= $data['tahun_ajaran_id'] == $ta['id'] ? 'selected' : '' ?>>
            <?= $ta['tahun_mulai'] ?>/<?= $ta['tahun_selesai'] ?> - <?= $ta['semester'] ?>
          </option>
        <?php } ?>
      </select>

      <button type="submit" class="button">Update</button>
      <a href="../../dashboard/data_jadwal.php" class="button button-kembali">Batal</a>
    </form>
  </div>
</div>
</body>
</html>
