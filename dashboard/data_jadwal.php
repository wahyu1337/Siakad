<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

// Ambil semua tahun ajaran
$tahun_ajaran_list = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY tahun_mulai DESC");

// Ambil tahun ajaran terpilih dari parameter GET (jika ada)
$tahun_ajaran_id = isset($_GET['tahun_ajaran_id']) ? (int)$_GET['tahun_ajaran_id'] : null;

// Query tahun ajaran aktif
$ta_aktif = mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE status_aktif = 1 LIMIT 1");
$ta_aktif_data = mysqli_fetch_assoc($ta_aktif);
$ada_ta_aktif = $ta_aktif_data ? true : false;

// Buat query utama jadwal
$sql = "
  SELECT 
    j.id, 
    j.hari, 
    j.jam_mulai, 
    j.jam_selesai, 
    d.nama AS nama_dosen, 
    mk.nama_mk, 
    mk.jurusan_id,
    jr.nama_jurusan,
    k.nama_kelas,
    ta.id AS ta_id,
    CONCAT(ta.tahun_mulai, '/', ta.tahun_selesai, ' ', ta.semester) AS tahun_ajaran,
    ta.status_aktif
  FROM jadwal j
  JOIN dosen d ON j.dosen_id = d.id
  JOIN matakuliah mk ON j.matakuliah_id = mk.id
  JOIN jurusan jr ON mk.jurusan_id = jr.id
  JOIN kelas k ON j.kelas_id = k.id
  JOIN tahun_ajaran ta ON j.tahun_ajaran_id = ta.id
";

// Filter jika memilih tahun ajaran tertentu
if ($tahun_ajaran_id) {
  $sql .= " WHERE j.tahun_ajaran_id = $tahun_ajaran_id ";
}

$sql .= " ORDER BY j.hari, j.jam_mulai ASC";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Jadwal</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/layout.css">
  <link rel="stylesheet" href="../css/dashboard.css">
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
      <a href="data_jadwal.php" class="active">🗓 Jadwal</a>
      <a href="data_nilai.php">📝 Nilai</a>
      <a href="data_tahun_ajaran.php">📆 Tahun Ajaran</a>
      <a href="data_users.php">🔐 User Login</a>
      <form action="../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Data Jadwal</h2>

        <!-- Filter Tahun Ajaran -->
        <form method="GET" class="filter-form" style="margin-bottom: 20px;">
          <label for="tahun_ajaran_id">Filter Tahun Ajaran:</label>
          <select name="tahun_ajaran_id" onchange="this.form.submit()">
            <option value="">-- Semua Tahun Ajaran --</option>
            <?php mysqli_data_seek($tahun_ajaran_list, 0); ?>
            <?php while ($ta = mysqli_fetch_assoc($tahun_ajaran_list)) : ?>
              <option value="<?= $ta['id'] ?>" <?= ($ta['id'] == $tahun_ajaran_id) ? 'selected' : '' ?>>
                <?= $ta['tahun_mulai'] ?>/<?= $ta['tahun_selesai'] ?> <?= $ta['semester'] ?>
                <?= $ta['status_aktif'] ? '(Aktif)' : '' ?>
              </option>
            <?php endwhile; ?>
          </select>
        </form>

        <!-- Tombol Tambah jika tahun ajaran aktif -->
        <?php if ($ada_ta_aktif): ?>
          <a href="../penambahan/tambah_jadwal.php" class="button">+ Tambah Jadwal</a>
        <?php else: ?>
          <p style="color: red; font-weight: bold;">Tidak ada tahun ajaran aktif. Tidak bisa menambahkan jadwal.</p>
        <?php endif; ?>

        <table>
          <thead>
            <tr>
              <th>Hari</th>
              <th>Jam</th>
              <th>Dosen</th>
              <th>Mata Kuliah</th>
              <th>Jurusan</th>
              <th>Kelas</th>
              <th>Tahun Ajaran</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($query) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($query)) : ?>
              <tr>
                <td><?= htmlspecialchars($row['hari']) ?></td>
                <td><?= substr($row['jam_mulai'], 0, 5) ?> - <?= substr($row['jam_selesai'], 0, 5) ?></td>
                <td><?= htmlspecialchars($row['nama_dosen']) ?></td>
                <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                <td><?= htmlspecialchars($row['nama_jurusan']) ?></td>
                <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                <td>
                  <?= htmlspecialchars($row['tahun_ajaran']) ?>
                  <?= $row['status_aktif'] ? '<span style="color:green;">(Aktif)</span>' : '<span style="color:red;">(Nonaktif)</span>' ?>
                </td>
                <td class="action-links">
                  <a href="../aksi/jadwal/edit_jadwal.php?id=<?= $row['id'] ?>">Edit</a> |
                  <a href="../aksi/jadwal/hapus_jadwal.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus jadwal ini?')">Hapus</a>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="8">Tidak ada jadwal ditemukan untuk tahun ajaran ini.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
