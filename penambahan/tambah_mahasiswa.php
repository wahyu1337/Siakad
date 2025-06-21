<?php
include '../koneksi.php';

// Ambil semua jurusan
$jurusan = mysqli_query($conn, "SELECT * FROM jurusan ORDER BY nama_jurusan ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Mahasiswa</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/style1.css">
</head>
<body>
  <div class="page-center">
    <div class="container">
      <h2 class="form-title">Form Tambah Mahasiswa</h2>

      <form action="../aksi/mahasiswa/simpan_mahasiswa.php" method="post">
        <label for="nim">NIM</label>
        <input type="text" name="nim" required>

        <label for="nama">Nama Mahasiswa</label>
        <input type="text" name="nama" required>

        <label for="jenis_kelamin">Jenis Kelamin</label>
        <select name="jenis_kelamin" required>
          <option value="">-- Pilih --</option>
          <option value="Laki-laki">Laki-laki</option>
          <option value="Perempuan">Perempuan</option>
        </select>

        <label for="jurusan">Jurusan</label>
        <select name="jurusan_id" required>
          <option value="">-- Pilih Jurusan --</option>
          <?php while ($j = mysqli_fetch_assoc($jurusan)) : ?>
            <option value="<?= $j['id'] ?>"><?= $j['nama_jurusan'] ?></option>
          <?php endwhile; ?>
        </select>

        <button type="submit" class="button">Simpan</button>
        <a href="../dashboard/data_mahasiswa.php" class="button button-kembali">Kembali</a>
      </form>
    </div>
  </div>
</body>
</html>