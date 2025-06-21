<?php
include '../koneksi.php';

// Ambil data jurusan dari DB
$jurusan = mysqli_query($conn, "SELECT * FROM jurusan");

if (isset($_POST['simpan'])) {
  $kode_mk   = $_POST['kode_mk'];
  $nama_mk   = $_POST['nama_mk'];
  $semester = $_POST['semester'];
  $sks       = $_POST['sks'];
  $jurusan_id = $_POST['jurusan'];

  $query = "INSERT INTO matakuliah (kode_mk, nama_mk, semester, sks, jurusan_id)
            VALUES ('$kode_mk', '$nama_mk', '$semester', '$sks', '$jurusan_id')";
  $insert = mysqli_query($conn, $query);

  if ($insert) {
    echo "<script>alert('Mata kuliah berhasil ditambahkan!'); window.location='../dashboard/data_matakuliah.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan mata kuliah: " . mysqli_error($conn) . "');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Mata Kuliah</title>
  <link rel="stylesheet" href="../css/style1.css">
</head>
<body>
  <div class="page-center">
    <div class="container">
      <h2 class="form-title">Form Tambah Mata Kuliah</h2>
      <form method="post">
        <label for="kode_mk">Kode Mata Kuliah</label>
        <input type="text" id="kode_mk" name="kode_mk" placeholder="Contoh: IF001" required>

        <label for="nama_mk">Nama Mata Kuliah</label>
        <input type="text" id="nama_mk" name="nama_mk" placeholder="Contoh: Pemrograman Web" required>

        <label>Semester:</label>
        <select name="semester" required>
          <option value="">-- Pilih Semester --</option>
          <?php for ($i = 1; $i <= 8; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?></option>
          <?php endfor; ?>
        </select>

        <label for="sks">Jumlah SKS</label>
        <input type="number" id="sks" name="sks" min="1" max="6" placeholder="Contoh: 3" required>

        <label for="jurusan">Jurusan</label>
        <select id="jurusan" name="jurusan" required>
          <option value="">-- Pilih Jurusan --</option>
          <?php while ($row = mysqli_fetch_assoc($jurusan)) {
            echo "<option value='{$row['id']}'>{$row['nama_jurusan']}</option>";
          } ?>
        </select>

        <button type="submit" name="simpan" class="button">Simpan</button>
        <a href="../dashboard/data_matakuliah.php" class="button button-nilai">Kembali</a>
      </form>
    </div>
  </div>
</body>
</html>
