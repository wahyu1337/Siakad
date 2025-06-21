<?php
include '../koneksi.php';

$tahun_ajaran = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY tahun_mulai DESC, semester DESC");
$dosen = mysqli_query($conn, "SELECT * FROM dosen ORDER BY nama ASC");
$kelas = mysqli_query($conn, "SELECT k.id, k.nama_kelas, j.nama_jurusan 
                           FROM kelas k JOIN jurusan j ON k.jurusan_id = j.id
                           ORDER BY j.nama_jurusan, k.nama_kelas");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $matakuliah_id = $_POST['matakuliah_id'];
  $dosen_id = $_POST['dosen_id'];
  $kelas_id = $_POST['kelas_id'];
  $hari = $_POST['hari'];
  $jam_mulai = $_POST['jam_mulai'];
  $jam_selesai = $_POST['jam_selesai'];
  $tahun_ajaran_id = $_POST['tahun_ajaran_id'];

  $simpan = mysqli_query($conn, "INSERT INTO jadwal 
    (matakuliah_id, dosen_id, kelas_id, hari, jam_mulai, jam_selesai, tahun_ajaran_id)
    VALUES ('$matakuliah_id', '$dosen_id', '$kelas_id', '$hari', '$jam_mulai', '$jam_selesai', '$tahun_ajaran_id')");

  if ($simpan) {
    header("Location: ../dashboard/data_jadwal.php");
    exit;
  } else {
    echo "<p style='color:red;text-align:center;'>Gagal menyimpan jadwal.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tambah Jadwal</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/style1.css">
</head>
<body>
  <div class="page-center">
    <div class="container">
      <h2 class="form-title">Tambah Jadwal</h2>
      <form method="post">

        <label for="tahun_ajaran_id">Tahun Ajaran</label>
        <select name="tahun_ajaran_id" id="tahun_ajaran_id" required>
          <option value="">-- Pilih --</option>
          <?php while ($ta = mysqli_fetch_assoc($tahun_ajaran)) { ?>
            <option value="<?= $ta['id'] ?>">
              <?= $ta['tahun_mulai'] ?>/<?= $ta['tahun_selesai'] ?> - <?= $ta['semester'] ?>
            </option>
          <?php } ?>
        </select>

        <label for="matakuliah_id">Mata Kuliah</label>
        <select name="matakuliah_id" id="matakuliah_id" required>
          <option value="">-- Pilih Tahun Ajaran Dulu --</option>
        </select>

        <label for="dosen_id">Dosen</label>
        <select name="dosen_id" id="dosen_id" required>
          <option value="">-- Pilih --</option>
          <?php while ($d = mysqli_fetch_assoc($dosen)) { ?>
            <option value="<?= $d['id'] ?>"><?= $d['nama'] ?></option>
          <?php } ?>
        </select>

        <label for="kelas_id">Kelas</label>
        <select name="kelas_id" id="kelas_id" required>
          <option value="">-- Pilih --</option>
          <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
            <option value="<?= $k['id'] ?>">
              <?= $k['nama_kelas'] ?> - <?= $k['nama_jurusan'] ?>
            </option>
          <?php } ?>
        </select>

        <label>Hari</label>
        <select name="hari" required>
          <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari) { ?>
            <option value="<?= $hari ?>"><?= $hari ?></option>
          <?php } ?>
        </select>

        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" required>

        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" required>

        <button type="submit" class="button">Simpan</button>
        <a href="../dashboard/data_jadwal.php" class="button button-kembali">Kembali</a>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('tahun_ajaran_id').addEventListener('change', function () {
      const tahunAjaranId = this.value;
      fetch('../ajax/get_matakuliah_by_semester.php?tahun_ajaran_id=' + tahunAjaranId)
        .then(response => response.text())
        .then(data => {
          document.getElementById('matakuliah_id').innerHTML = data;
        });
    });
  </script>
</body>
</html>
