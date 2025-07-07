<?php
include '../koneksi.php';

$tahun_ajaran = mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE status_aktif = 1 ORDER BY tahun_mulai DESC LIMIT 1");
$ta_aktif = mysqli_fetch_assoc($tahun_ajaran);

if (!$ta_aktif) {
  echo "<p style='color:red;text-align:center;'>Tidak ada tahun ajaran aktif. Silakan aktifkan satu tahun ajaran terlebih dahulu.</p>";
  exit;
}

$tahun_ajaran_id = $ta_aktif['id'];
$dosen = mysqli_query($conn, "SELECT * FROM dosen");
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $matakuliah_id = $_POST['matakuliah_id'];
  $dosen_id = $_POST['dosen_id'];
  $kelas_id = $_POST['kelas_id'];
  $hari = $_POST['hari'];
  $jam_mulai = $_POST['jam_mulai'];
  $jam_selesai = $_POST['jam_selesai'];

  // Cek bentrok dosen
  $cek_dosen = mysqli_query($conn, "SELECT * FROM jadwal WHERE dosen_id = '$dosen_id' AND hari = '$hari' AND tahun_ajaran_id = '$tahun_ajaran_id' AND (
    (jam_mulai <= '$jam_mulai' AND jam_selesai > '$jam_mulai') OR
    (jam_mulai < '$jam_selesai' AND jam_selesai >= '$jam_selesai') OR
    ('$jam_mulai' <= jam_mulai AND '$jam_selesai' > jam_mulai)
  )");

  // Cek bentrok kelas
  $cek_kelas = mysqli_query($conn, "SELECT * FROM jadwal WHERE kelas_id = '$kelas_id' AND hari = '$hari' AND tahun_ajaran_id = '$tahun_ajaran_id' AND (
    (jam_mulai <= '$jam_mulai' AND jam_selesai > '$jam_mulai') OR
    (jam_mulai < '$jam_selesai' AND jam_selesai >= '$jam_selesai') OR
    ('$jam_mulai' <= jam_mulai AND '$jam_selesai' > jam_mulai)
  )");

  // Cek duplikat matakuliah di kelas tersebut
  $cek_duplikat_mk = mysqli_query($conn, "SELECT * FROM jadwal WHERE matakuliah_id = '$matakuliah_id' AND kelas_id = '$kelas_id' AND tahun_ajaran_id = '$tahun_ajaran_id'");

  if (mysqli_num_rows($cek_dosen) > 0) {
    $error = "Jadwal bentrok! Dosen sudah mengajar di waktu tersebut.";
  } elseif (mysqli_num_rows($cek_kelas) > 0) {
    $error = "Jadwal bentrok! Kelas sudah memiliki jadwal lain di waktu tersebut.";
  } elseif (mysqli_num_rows($cek_duplikat_mk) > 0) {
    $error = "Mata kuliah ini sudah dijadwalkan untuk kelas tersebut dalam tahun ajaran aktif.";
  } else {
    // Ambil jurusan_id dari kelas
    $kelas_data = mysqli_query($conn, "SELECT jurusan_id FROM kelas WHERE id = '$kelas_id'");
    $kelas = mysqli_fetch_assoc($kelas_data);
    $jurusan_id = $kelas['jurusan_id'] ?? null;

    $simpan = mysqli_query($conn, "INSERT INTO jadwal (matakuliah_id, dosen_id, kelas_id, hari, jam_mulai, jam_selesai, tahun_ajaran_id, jurusan_id)
      VALUES ('$matakuliah_id', '$dosen_id', '$kelas_id', '$hari', '$jam_mulai', '$jam_selesai', '$tahun_ajaran_id', '$jurusan_id')");

    if ($simpan) {
      header("Location: ../dashboard/data_jadwal.php");
      exit;
    } else {
      $error = "Gagal menyimpan jadwal.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Jadwal</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/style1.css">
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const mkSelect = document.getElementById('matakuliah_id');
    const kelasSelect = document.getElementById('kelas_id');

    fetch(`../ajax/get_matakuliah.php?tahun_ajaran_id=<?= $tahun_ajaran_id ?>`)
      .then(res => res.text())
      .then(data => {
        mkSelect.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>' + data;
      });

    mkSelect.addEventListener('change', function () {
      const mkId = this.value;
      if (mkId) {
        fetch(`../ajax/get_kelas_by_matakuliah.php?matakuliah_id=${mkId}`)
          .then(res => res.text())
          .then(data => {
            kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>' + data;
          });
      } else {
        kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
      }
    });
  });
  </script>
</head>
<body>
<div class="page-center">
  <div class="container">
    <h2 class="form-title">Tambah Jadwal (<?= $ta_aktif['tahun_mulai'] ?>/<?= $ta_aktif['tahun_selesai'] ?> <?= $ta_aktif['semester'] ?>)</h2>

    <?php if ($error) echo "<p style='color:red;text-align:center;'>$error</p>"; ?>

    <form method="post">
      <label for="matakuliah_id">Mata Kuliah</label>
      <select name="matakuliah_id" id="matakuliah_id" required>
        <option value="">-- Pilih Mata Kuliah --</option>
      </select>

      <label for="dosen_id">Dosen</label>
      <select name="dosen_id" required>
        <option value="">-- Pilih Dosen --</option>
        <?php while ($d = mysqli_fetch_assoc($dosen)) { ?>
          <option value="<?= $d['id'] ?>"><?= $d['nama'] ?></option>
        <?php } ?>
      </select>

      <label for="kelas_id">Kelas</label>
      <select name="kelas_id" id="kelas_id" required>
        <option value="">-- Pilih Kelas --</option>
      </select>

      <label>Hari</label>
      <select name="hari" required>
        <?php foreach (["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"] as $hari) { ?>
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
</body>
</html>