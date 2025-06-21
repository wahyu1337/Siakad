<?php
include '../../koneksi.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id = '$id'");
$mahasiswa = mysqli_fetch_assoc($result);

// Ambil jurusan
$jurusan = mysqli_query($conn, "SELECT * FROM jurusan");

if (isset($_POST['update'])) {
  $nim = $_POST['nim'];
  $nama = $_POST['nama'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $jurusan_id = $_POST['jurusan_id'];

  $update = mysqli_query($conn, "UPDATE mahasiswa SET 
    nim = '$nim',
    nama = '$nama',
    jenis_kelamin = '$jenis_kelamin',
    jurusan_id = '$jurusan_id'
    WHERE id = '$id'");

  if ($update) {
    header("Location: ../../dashboard/data_mahasiswa.php");
    exit;
  } else {
    echo "Gagal update data!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Mahasiswa</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/style1.css">
</head>
<body>
  <div class="page-center">
    <div class="container">
      <h2 class="form-title">Edit Data Mahasiswa</h2>
      <form method="post">
        <label>NIM</label>
        <input type="text" name="nim" value="<?= $mahasiswa['nim'] ?>" required>

        <label>Nama</label>
        <input type="text" name="nama" value="<?= $mahasiswa['nama'] ?>" required>

        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" required>
          <option value="Laki-laki" <?= $mahasiswa['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
          <option value="Perempuan" <?= $mahasiswa['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
        </select>

        <label>Jurusan</label>
        <select name="jurusan_id" required>
          <?php while ($row = mysqli_fetch_assoc($jurusan)) { ?>
            <option value="<?= $row['id'] ?>" <?= $row['id'] == $mahasiswa['jurusan_id'] ? 'selected' : '' ?>>
              <?= $row['nama_jurusan'] ?>
            </option>
          <?php } ?>
        </select>

        <button type="submit" name="update" class="button">Simpan Perubahan</button>
        <a href="../../dashboard/data_mahasiswa.php" class="button button-kembali">Kembali</a>
      </form>
    </div>
  </div>
</body>
</html>
