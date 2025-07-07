<?php
session_start();
include '../../koneksi.php';

// Cek login mahasiswa
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
  header("Location: ../../login.php");
  exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];

// Ambil tahun ajaran aktif
$tahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE status_aktif = 1 LIMIT 1");
$tahun_aktif = mysqli_fetch_assoc($tahun);
$id_tahun_ajaran = $tahun_aktif['id'];
$semester = strtolower($tahun_aktif['semester']) === 'genap' ? 2 : 1;

// Ambil jurusan mahasiswa
$getJurusan = mysqli_query($conn, "
  SELECT j.id FROM mahasiswa m
  JOIN jurusan j ON m.jurusan_id = j.id
  WHERE m.id = $mahasiswa_id
");
$jurusan = mysqli_fetch_assoc($getJurusan);
$id_jurusan = $jurusan['id'];

// Ambil data jadwal berdasarkan jurusan & tahun ajaran
$jadwal = mysqli_query($conn, "
  SELECT j.id AS jadwal_id, m.nama_mk, m.sks, k.nama_kelas, j.hari, j.jam_mulai, j.jam_selesai
  FROM jadwal j
  JOIN matakuliah m ON j.matakuliah_id = m.id
  JOIN kelas k ON j.kelas_id = k.id
  WHERE m.jurusan_id = $id_jurusan AND j.tahun_ajaran_id = $id_tahun_ajaran
  ORDER BY j.hari, j.jam_mulai
");

// Ambil KRS yang sudah dipilih
$krs_saya = mysqli_query($conn, "SELECT jadwal_id FROM krs WHERE mahasiswa_id = $mahasiswa_id AND tahun_ajaran_id = $id_tahun_ajaran");
$dipilih = [];
while ($row = mysqli_fetch_assoc($krs_saya)) {
  $dipilih[] = $row['jadwal_id'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit KRS</title>
  <link rel="stylesheet" href="../../css/layout.css">
  <link rel="stylesheet" href="../../css/krs.css">
</head>
<body>
  <div class="container">
    <h2>Edit Kartu Rencana Studi</h2>
    <form action="../../aksi/krs/simpan_krs.php" method="post">
      <input type="hidden" name="edit" value="1">
      <table>
        <thead>
          <tr>
            <th>Pilih</th>
            <th>Mata Kuliah</th>
            <th>Kelas</th>
            <th>Hari</th>
            <th>Jam</th>
            <th>SKS</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($data = mysqli_fetch_assoc($jadwal)) : ?>
            <tr>
              <td><input type="checkbox" name="jadwal_id[]" value="<?= $data['jadwal_id'] ?>" <?= in_array($data['jadwal_id'], $dipilih) ? 'checked' : '' ?>></td>
              <td><?= htmlspecialchars($data['nama_mk']) ?></td>
              <td><?= htmlspecialchars($data['nama_kelas']) ?></td>
              <td><?= $data['hari'] ?></td>
              <td><?= substr($data['jam_mulai'], 0, 5) . ' - ' . substr($data['jam_selesai'], 0, 5) ?></td>
              <td><?= $data['sks'] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <button type="submit">Simpan Perubahan</button>
    </form>
    <br>
    <a href="krs_saya.php"><< Kembali</a>
  </div>
</body>
</html>