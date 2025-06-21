<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../index.php");
  exit;
}

$id = $_GET['id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $id"));

if (!$user) {
  die("User tidak ditemukan.");
}

$mahasiswa = mysqli_query($conn, "SELECT id, nim, nama FROM mahasiswa ORDER BY nama ASC");
$dosen = mysqli_query($conn, "SELECT id, nip, nama FROM dosen ORDER BY nama ASC");

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $role = $_POST['role'];
  $mhs_id = isset($_POST['mahasiswa_id']) ? $_POST['mahasiswa_id'] : null;
  $dsn_id = isset($_POST['dosen_id']) ? $_POST['dosen_id'] : null;

  $update = mysqli_query($conn, "
    UPDATE users SET
      username = '$username',
      role = '$role',
      mahasiswa_id = " . ($mhs_id ? "'$mhs_id'" : "NULL") . ",
      dosen_id = " . ($dsn_id ? "'$dsn_id'" : "NULL") . "
    WHERE id = $id
  ");

  if ($update) {
    header("Location: ../../dashboard/data_users.php");
    exit;
  } else {
    $error = "Gagal mengedit user.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/style1.css">
  <script>
    function toggleFields() {
      const role = document.getElementById('role').value;
      document.getElementById('mahasiswa_select').style.display = role === 'mahasiswa' ? 'block' : 'none';
      document.getElementById('dosen_select').style.display = role === 'dosen' ? 'block' : 'none';
    }
  </script>
</head>
<body onload="toggleFields()">
  <div class="page-center">
    <div class="container">
      <h2 class="form-title">Edit User Login</h2>

      <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

      <form method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" style="width: 88%"required value="<?= htmlspecialchars($user['username']) ?>">

        <label for="role">Role</label>
        <select name="role" id="role" required onchange="toggleFields()">
          <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
          <option value="mahasiswa" <?= $user['role'] == 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
          <option value="dosen" <?= $user['role'] == 'dosen' ? 'selected' : '' ?>>Dosen</option>
        </select>

        <div id="mahasiswa_select" style="display:none">
          <label for="mahasiswa_id">Pilih Mahasiswa</label>
          <select name="mahasiswa_id">
            <option value="">-- Pilih Mahasiswa --</option>
            <?php while ($m = mysqli_fetch_assoc($mahasiswa)) { ?>
              <option value="<?= $m['id'] ?>" <?= ($user['mahasiswa_id'] == $m['id']) ? 'selected' : '' ?>>[<?= $m['nim'] ?>] <?= $m['nama'] ?></option>
            <?php } ?>
          </select>
        </div>

        <div id="dosen_select" style="display:none">
          <label for="dosen_id">Pilih Dosen</label>
          <select name="dosen_id">
            <option value="">-- Pilih Dosen --</option>
            <?php while ($d = mysqli_fetch_assoc($dosen)) { ?>
              <option value="<?= $d['id'] ?>" <?= ($user['dosen_id'] == $d['id']) ? 'selected' : '' ?>>[<?= $d['nip'] ?>] <?= $d['nama'] ?></option>
            <?php } ?>
          </select>
        </div>

        <button type="submit" name="submit" class="button">Simpan</button>
        <a href="../../dashboard/data_users.php" class="button button-kembali">Kembali</a>
      </form>
    </div>
  </div>
</body>
</html>
