<?php
include '../../koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM dosen WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
  echo "Dosen tidak ditemukan!";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nip = $_POST['nip'];
  $nama = $_POST['nama'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $email = $_POST['email'];
  $bidang = $_POST['bidang'];

  $update = mysqli_query($conn, "UPDATE dosen SET 
                    nip = '$nip',
                    nama = '$nama',
                    jenis_kelamin = '$jenis_kelamin',
                    email = '$email',
                    bidang = '$bidang'
                    WHERE id = $id");

  if ($update) {
    header("Location: ../../dashboard/data_dosen.php");
    exit;
  } else {
    echo "<p style='color:red;text-align:center;'>Gagal mengupdate data!</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Dosen</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/style1.css">
</head>
<body>
  <div class="page-center">
    <div class="container">
      <h2 class="form-title">Form Edit Dosen</h2>
      <form method="post">
        <label>NIP</label>
        <input type="text" name="nip" value="<?= $data['nip'] ?>" required>

        <label>Nama Dosen</label>
        <input type="text" name="nama" value="<?= $data['nama'] ?>" required>

        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" required>
          <option value="Laki-laki" <?= $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
          <option value="Perempuan" <?= $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
        </select>

        <label>Email</label>
        <input type="email" name="email" value="<?= $data['email'] ?>">

        <label>Bidang</label>
        <input type="text" name="bidang" value="<?= $data['bidang'] ?>">

        <button type="submit" class="button">Simpan Perubahan</button>
        <a href="../../dashboard/data_dosen.php" class="button button-kembali">Kembali</a>
      </form>
    </div>
  </div>
</body>
</html>
