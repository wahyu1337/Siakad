<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

$keyword = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$tahun_filter = isset($_GET['tahun_ajaran']) ? intval($_GET['tahun_ajaran']) : 0;

$tahun_opsi = mysqli_query($conn, "SELECT id, tahun_mulai, tahun_selesai, semester FROM tahun_ajaran ORDER BY tahun_mulai DESC, semester DESC");

$sql = "
  SELECT 
    n.id,
    m.nim,
    m.nama AS nama_mahasiswa,
    mk.nama_mk,
    n.nilai_angka,
    ta.semester AS smt,
    ta.tahun_mulai,
    ta.tahun_selesai
  FROM nilai n
  JOIN krs k ON n.krs_id = k.id
  JOIN mahasiswa m ON k.mahasiswa_id = m.id
  JOIN matakuliah mk ON k.matakuliah_id = mk.id
  JOIN tahun_ajaran ta ON k.tahun_ajaran_id = ta.id
  WHERE 1=1
";

if ($tahun_filter) {
  $sql .= " AND ta.id = $tahun_filter";
}

if (!empty($keyword)) {
  $sql .= " AND (
    m.nim LIKE '%$keyword%' OR 
    m.nama LIKE '%$keyword%' OR 
    mk.nama_mk LIKE '%$keyword%'
  )";
}

$sql .= " ORDER BY m.nama ASC, ta.tahun_mulai DESC, ta.semester DESC";

$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Nilai Mahasiswa</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/layout.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    form.filter-form { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; align-items: center; }
    form.filter-form select, form.filter-form input[type="text"] { padding: 5px; font-size: 14px; }
  </style>
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../logo.png" class="logo-fixed" alt="Logo">
    </div>
    <div class="header-center">
      <h1>Sistem Akademik - Admin</h1>
      <p>Halo, <strong><?= $_SESSION['username'] ?></strong> (Admin)</p>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="admin_dashboard.php">🏠 Dashboard</a>
      <a href="data_mahasiswa.php">👨‍🎓 Mahasiswa</a>
      <a href="data_dosen.php">👨‍🏫 Dosen</a>
      <a href="data_jurusan.php">📚 Jurusan</a>
      <a href="data_matakuliah.php">📖 Mata Kuliah</a>
      <a href="data_kelas.php">🏫 Kelas</a>
      <a href="data_jadwal.php">🗓 Jadwal</a>
      <a href="data_nilai.php" class="active">📝 Nilai</a>
      <a href="data_tahun_ajaran.php">📆 Tahun Ajaran</a>
      <a href="data_users.php">🔐 User Login</a>
      <form action="../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Data Nilai Mahasiswa</h2>

        <form method="get" class="filter-form">
          <select name="tahun_ajaran" onchange="this.form.submit()">
            <option value="0">-- Semua Tahun Ajaran --</option>
            <?php while ($ta = mysqli_fetch_assoc($tahun_opsi)): ?>
              <?php
                $label = $ta['tahun_mulai'] . '/' . $ta['tahun_selesai'] . ' - ' . ucfirst($ta['semester']);
                $selected = ($tahun_filter == $ta['id']) ? 'selected' : '';
              ?>
              <option value="<?= $ta['id'] ?>" <?= $selected ?>><?= $label ?></option>
            <?php endwhile; ?>
          </select>

          <input type="text" name="search" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari NIM, Nama, atau Mata Kuliah">
          <button type="submit">🔍 Cari</button>
        </form>

        <table>
          <thead>
            <tr>
              <th>NIM</th>
              <th>Nama Mahasiswa</th>
              <th>Mata Kuliah</th>
              <th>Nilai</th>
              <th>Semester</th>
              <th>Tahun Ajaran</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($query) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                  <td><?= htmlspecialchars($row['nim']) ?></td>
                  <td><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                  <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                  <td><?= htmlspecialchars($row['nilai_angka']) ?></td>
                  <td><?= ucfirst($row['smt']) ?></td>
                  <td><?= "{$row['tahun_mulai']}/{$row['tahun_selesai']}" ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="6">Tidak ada data nilai yang sesuai.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>