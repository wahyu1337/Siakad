<?php
session_start();
include '../../koneksi.php';

// Hanya role admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../../index.php");
  exit;
}

// Validasi ID
if (!isset($_GET['id'])) {
  echo "ID jadwal tidak ditemukan.";
  exit;
}

$id = intval($_GET['id']);
$jadwal = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT * FROM jadwal WHERE id = $id
"));
if (!$jadwal) {
  echo "Data jadwal tidak ditemukan.";
  exit;
}

// Ambil data pendukung
$dosen = mysqli_query($conn, "SELECT * FROM dosen ORDER BY nama ASC");
$matakuliah = mysqli_query($conn, "SELECT * FROM matakuliah ORDER BY nama_mk ASC");
$kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$tahun_ajaran = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY tahun_mulai DESC");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $matakuliah_id = intval($_POST['matakuliah_id']);
  $kelas_id = intval($_POST['kelas_id']);
  $dosen_id = intval($_POST['dosen_id']);
  $hari = mysqli_real_escape_string($conn, $_POST['hari']);
  $jam_mulai = $_POST['jam_mulai'];
  $jam_selesai = $_POST['jam_selesai'];
  $tahun_ajaran_id = intval($_POST['tahun_ajaran_id']);

  // Validasi bentrok waktu untuk dosen
  $cek_bentrok = mysqli_query($conn, "
    SELECT * FROM jadwal 
    WHERE dosen_id = $dosen_id 
      AND hari = '$hari' 
      AND id != $id
      AND tahun_ajaran_id = $tahun_ajaran_id
      AND (
        (jam_mulai <= '$jam_mulai' AND jam_selesai > '$jam_mulai') OR
        (jam_mulai < '$jam_selesai' AND jam_selesai >= '$jam_selesai') OR
        ('$jam_mulai' <= jam_mulai AND '$jam_selesai' > jam_mulai)
      )
  ");

  if (mysqli_num_rows($cek_bentrok) > 0) {
    $error = "Dosen sudah mengajar di waktu tersebut.";
  } else {
    $update = mysqli_query($conn, "
      UPDATE jadwal SET 
        matakuliah_id = $matakuliah_id,
        kelas_id = $kelas_id,
        dosen_id = $dosen_id,
        hari = '$hari',
        jam_mulai = '$jam_mulai',
        jam_selesai = '$jam_selesai',
        tahun_ajaran_id = $tahun_ajaran_id
      WHERE id = $id
    ");

    if ($update) {
      header("Location: ../../dashboard/data_jadwal.php");
      exit;
    } else {
      $error = "Gagal menyimpan perubahan.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
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
    <?php if ($error) echo "<p style='color:red;text-align:center;'>$error</p>"; ?>
    <form method="post">
      <label for="matakuliah_id">Mata Kuliah</label>
      <select name="matakuliah_id" required>
        <?php while ($mk = mysqli_fetch_assoc($matakuliah)) { ?>
          <option value="<?= $mk['id'] ?>" <?= $mk['id'] == $jadwal['matakuliah_id'] ? 'selected' : '' ?>>
            <?= $mk['nama_mk'] ?>
          </option>
        <?php } ?>
      </select>

      <label for="kelas_id">Kelas</label>
      <select name="kelas_id" required>
        <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
          <option value="<?= $k['id'] ?>" <?= $k['id'] == $jadwal['kelas_id'] ? 'selected' : '' ?>>
            <?= $k['nama_kelas'] ?>
          </option>
        <?php } ?>
      </select>

      <label for="dosen_id">Dosen</label>
      <select name="dosen_id" required>
        <?php while ($d = mysqli_fetch_assoc($dosen)) { ?>
          <option value="<?= $d['id'] ?>" <?= $d['id'] == $jadwal['dosen_id'] ? 'selected' : '' ?>>
            <?= $d['nama'] ?>
          </option>
        <?php } ?>
      </select>

      <label for="hari">Hari</label>
      <select name="hari" required>
        <?php foreach (["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"] as $hari) { ?>
          <option value="<?= $hari ?>" <?= $hari == $jadwal['hari'] ? 'selected' : '' ?>>
            <?= $hari ?>
          </option>
        <?php } ?>
      </select>

      <label>Jam Mulai</label>
      <input type="time" name="jam_mulai" value="<?= $jadwal['jam_mulai'] ?>" required>

      <label>Jam Selesai</label>
      <input type="time" name="jam_selesai" value="<?= $jadwal['jam_selesai'] ?>" required>

      <label>Tahun Ajaran</label>
      <select name="tahun_ajaran_id" required>
        <?php while ($ta = mysqli_fetch_assoc($tahun_ajaran)) { ?>
          <option value="<?= $ta['id'] ?>" <?= $ta['id'] == $jadwal['tahun_ajaran_id'] ? 'selected' : '' ?>>
            <?= $ta['tahun_mulai'] ?>/<?= $ta['tahun_selesai'] ?> (<?= $ta['semester'] ?>)
          </option>
        <?php } ?>
      </select>

      <button type="submit" class="button">Simpan</button>
      <a href="../../dashboard/data_jadwal.php" class="button button-kembali">Kembali</a>
    </form>
  </div>
</div>
</body>
</html>