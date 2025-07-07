<?php
include '../koneksi.php';

$dosen = mysqli_query($conn, "SELECT id, nama FROM dosen");
$mahasiswa = mysqli_query($conn, "SELECT id, nama FROM mahasiswa");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password_raw = $_POST['password'];
  $role = $_POST['role'];
  $dosen_id = $_POST['dosen_id'] ?? NULL;
  $mahasiswa_id = $_POST['mahasiswa_id'] ?? NULL;

  // Validasi wajib isi
  if (!$username || !$password_raw || !$role) {
    $error = "Semua field wajib diisi!";
  } else {
    // Cek duplikat username
    $cek = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
      $error = "Username sudah digunakan!";
    } else {
      $password = password_hash($password_raw, PASSWORD_DEFAULT);

      $query = "INSERT INTO users (username, password, role, dosen_id, mahasiswa_id) VALUES (
        '$username', '$password', '$role',
        " . ($dosen_id ? "'$dosen_id'" : "NULL") . ",
        " . ($mahasiswa_id ? "'$mahasiswa_id'" : "NULL") . "
      )";

      $simpan = mysqli_query($conn, $query);

      if ($simpan) {
        header("Location: ../dashboard/data_users.php");
        exit;
      } else {
        $error = "Gagal menambahkan user: " . mysqli_error($conn);
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tambah User</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/style1.css">
</head>
<body>
<div class="page-center">
  <div class="container">
    <h2 class="form-title">Tambah User Login</h2>
    <form method="post">
      <label>Username</label>
      <input type="text" name="username" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <label>Role</label>
      <select name="role" id="role" required onchange="toggleRelasi()">
        <option value="">-- Pilih --</option>
        <option value="admin">Admin</option>
        <option value="dosen">Dosen</option>
        <option value="mahasiswa">Mahasiswa</option>
      </select>

      <div id="dosenSelect" style="display:none;">
        <label>Dosen</label>
        <select name="dosen_id">
          <option value="">-- Pilih --</option>
          <?php while ($d = mysqli_fetch_assoc($dosen)) { ?>
            <option value="<?= $d['id'] ?>"><?= $d['nama'] ?></option>
          <?php } ?>
        </select>
      </div>

      <div id="mahasiswaSelect" style="display:none;">
        <label>Mahasiswa</label>
        <select name="mahasiswa_id">
          <option value="">-- Pilih --</option>
          <?php while ($m = mysqli_fetch_assoc($mahasiswa)) { ?>
            <option value="<?= $m['id'] ?>"><?= $m['nama'] ?></option>
          <?php } ?>
        </select>
      </div>

      <button type="submit" class="button">Simpan</button>
      <a href="../dashboard/data_users.php" class="button button-kembali">Kembali</a>
    </form>
  </div>
</div>

<script>
function toggleRelasi() {
  const role = document.getElementById('role').value;
  document.getElementById('dosenSelect').style.display = role === 'dosen' ? 'block' : 'none';
  document.getElementById('mahasiswaSelect').style.display = role === 'mahasiswa' ? 'block' : 'none';
}
</script>
</body>
</html>
