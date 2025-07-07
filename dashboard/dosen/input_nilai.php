<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen' || !isset($_SESSION['dosen_id'])) {
    header("Location: ../../index.php");
    exit;
}

$dosen_id = intval($_SESSION['dosen_id']);
$username = $_SESSION['username'] ?? '';

// Ambil info dosen
$result = mysqli_query($conn, "SELECT nama, email FROM dosen WHERE id = $dosen_id");
if (!$result || mysqli_num_rows($result) === 0) {
  echo "<p style='color:red;text-align:center;'>Data dosen tidak ditemukan di database!</p>";
  exit;
}
$dosen = mysqli_fetch_assoc($result);

$message = '';

// Proses simpan nilai
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nilai']) && isset($_POST['jadwal_id'])) {
    $jadwal_id = intval($_POST['jadwal_id']);

    $cek_jadwal = mysqli_query($conn, "SELECT * FROM jadwal WHERE id = $jadwal_id AND dosen_id = $dosen_id");
    if (mysqli_num_rows($cek_jadwal) > 0) {
        $berhasil = 0;
        $gagal = 0;

        foreach ($_POST['nilai'] as $krs_id => $nilai) {
            $krs_id = intval($krs_id);
            $nilai = intval($nilai);

            $cek_nilai = mysqli_query($conn, "SELECT id FROM nilai WHERE krs_id = $krs_id");

            if (mysqli_num_rows($cek_nilai) > 0) {
                $update = mysqli_query($conn, "UPDATE nilai SET nilai_angka = $nilai WHERE krs_id = $krs_id");
                $update ? $berhasil++ : $gagal++;
            } else {
                $insert = mysqli_query($conn, "INSERT INTO nilai (krs_id, nilai_angka) VALUES ($krs_id, $nilai)");
                $insert ? $berhasil++ : $gagal++;
            }
        }

        $message = "<div class='message-success'>$berhasil nilai berhasil disimpan, $gagal gagal.</div>";
    } else {
        $message = "<div class='message-error'>Akses jadwal tidak valid!</div>";
    }
}

// Dropdown filter
$tahun_ajaran_result = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY tahun_mulai DESC");
$filter_ta = $_POST['tahun_ajaran_id'] ?? '';
$selected_jadwal = $_POST['jadwal_id'] ?? '';

$jadwal_query = "
    SELECT j.id AS jadwal_id, mk.nama_mk, k.nama_kelas 
    FROM jadwal j
    JOIN matakuliah mk ON j.matakuliah_id = mk.id
    JOIN kelas k ON j.kelas_id = k.id
    WHERE j.dosen_id = $dosen_id
";
if ($filter_ta) $jadwal_query .= " AND j.tahun_ajaran_id = " . intval($filter_ta);
$jadwal_result = mysqli_query($conn, $jadwal_query);

$krs_result = null;
if ($selected_jadwal) {
    $krs_result = mysqli_query($conn, "
        SELECT k.id AS krs_id, m.nim, m.nama, 
        (SELECT nilai_angka FROM nilai WHERE krs_id = k.id) AS nilai_angka
        FROM krs k
        JOIN mahasiswa m ON k.mahasiswa_id = m.id
        WHERE k.jadwal_id = " . intval($selected_jadwal) . "
        ORDER BY m.nama ASC
    ");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Nilai</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/layout.css">
  <link rel="stylesheet" href="../../css/dashboard.css">
  <style>
    .table-nilai { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .table-nilai th, .table-nilai td { padding: 8px; border: 1px solid #ccc; text-align: center; }
    .message-success, .message-error {
      margin-bottom: 15px;
      padding: 10px;
      border-radius: 4px;
      text-align: center;
    }
    .message-success { color: green; background: #e8f5e9; }
    .message-error { color: red; background: #ffebee; }
    .sidebar-user {
      color: white;
      text-align: center;
      padding: 15px;
      background-color: #4f46e5;
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    .sidebar-user strong {
      font-size: 1.1em;
      display: block;
    }
    .sidebar-user small {
      color: #ccc;
      font-size: 0.9em;
    }
  </style>
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../../logo.png" class="logo-fixed" alt="Logo">
    </div>
    <div class="header-center">
      <h1>Input Nilai Mahasiswa</h1>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <div class="sidebar-user">
        <strong><?= htmlspecialchars($dosen['nama']) ?></strong>
        <small><?= htmlspecialchars($dosen['email']) ?></small>
      </div>
      <a href="../dashboard_dosen.php">🏠 Dashboard</a>
      <a href="jadwal_saya.php">📅 Jadwal Saya</a>
      <a href="input_nilai.php" class="active">📝 Input Nilai</a>
      <a href="profil/profile_dosen.php">👤 Profil</a>
      <form action="../../logout.php" method="post">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <?= $message ?>
        <form method="post">
          <label for="tahun_ajaran_id">Tahun Ajaran:</label>
          <select name="tahun_ajaran_id" onchange="this.form.submit()">
            <option value="">-- Semua Tahun Ajaran --</option>
            <?php while ($ta = mysqli_fetch_assoc($tahun_ajaran_result)): ?>
              <option value="<?= $ta['id'] ?>" <?= ($filter_ta == $ta['id']) ? 'selected' : '' ?>>
                <?= $ta['tahun_mulai'] ?>/<?= $ta['tahun_selesai'] ?> - <?= ucfirst($ta['semester']) ?>
              </option>
            <?php endwhile; ?>
          </select>

          <br><br>
          <label for="jadwal_id">Pilih Jadwal:</label>
          <select name="jadwal_id" onchange="this.form.submit()" required>
            <option value="">-- Pilih Jadwal --</option>
            <?php while ($row = mysqli_fetch_assoc($jadwal_result)): ?>
              <option value="<?= $row['jadwal_id'] ?>" <?= ($selected_jadwal == $row['jadwal_id']) ? 'selected' : '' ?>>
                <?= $row['nama_mk'] ?> (<?= $row['nama_kelas'] ?>)
              </option>
            <?php endwhile; ?>
          </select>
        </form>

        <?php if ($krs_result && mysqli_num_rows($krs_result) > 0): ?>
          <form method="post">
            <input type="hidden" name="jadwal_id" value="<?= $selected_jadwal ?>">
            <table class="table-nilai">
              <thead>
                <tr>
                  <th>No</th>
                  <th>NIM</th>
                  <th>Nama</th>
                  <th>Nilai Angka</th>
                  <th>Nilai Huruf</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($krs_result)): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nim']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td>
                      <input type="number" 
                             name="nilai[<?= $row['krs_id'] ?>]" 
                             class="nilai-angka" 
                             value="<?= $row['nilai_angka'] ?? '' ?>" 
                             min="0" max="100" 
                             oninput="updateNilaiHuruf(this)" required>
                    </td>
                    <td class="nilai-huruf">-</td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
            <br>
            <button type="submit">💾 Simpan Nilai</button>
          </form>
        <?php elseif ($selected_jadwal): ?>
          <p class="message-error">Tidak ada mahasiswa pada jadwal ini.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    function updateNilaiHuruf(input) {
      const nilai = parseInt(input.value);
      const tdHuruf = input.parentElement.nextElementSibling;
      let huruf = "-";

      if (!isNaN(nilai)) {
        if (nilai >= 85) huruf = "A";
        else if (nilai >= 75) huruf = "B";
        else if (nilai >= 65) huruf = "C";
        else if (nilai >= 50) huruf = "D";
        else huruf = "E";
      }

      tdHuruf.textContent = huruf;
    }

    document.querySelectorAll('.nilai-angka').forEach(updateNilaiHuruf);
  </script>
</body>
</html>