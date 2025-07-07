<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['mahasiswa_id'])) {
  header("Location: ../../login.php");
  exit();
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];

// Ambil tahun ajaran aktif
$tahunAjaran = mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE status_aktif = 1 LIMIT 1");
$dataTA = mysqli_fetch_assoc($tahunAjaran);
$tahun_ajaran_id = $dataTA['id'];
$semester = (int)$dataTA['semester']; // Pastikan integer: 1 = Ganjil, 2 = Genap

if (!isset($_POST['jadwal_id']) || empty($_POST['jadwal_id'])) {
  echo "<script>alert('Tidak ada mata kuliah yang dipilih!'); window.location.href='../../dashboard/mahasiswa/isi_krs.php';</script>";
  exit();
}

$total_sks = 0;
$max_sks = 24;

foreach ($_POST['jadwal_id'] as $jadwal_id) {
  // Ambil SKS dari matakuliah
  $qSks = mysqli_query($conn, "
    SELECT m.sks FROM jadwal j
    JOIN matakuliah m ON j.matakuliah_id = m.id
    WHERE j.id = $jadwal_id
  ");
  $dataSks = mysqli_fetch_assoc($qSks);
  $sks = (int)$dataSks['sks'];

  // Cek duplikat
  $cek = mysqli_query($conn, "SELECT * FROM krs WHERE mahasiswa_id = $mahasiswa_id AND jadwal_id = $jadwal_id");

  if (mysqli_num_rows($cek) == 0) {
    $total_sks += $sks;

    if ($total_sks > $max_sks) {
      echo "<script>alert('Total SKS melebihi batas maksimum (24 SKS)!'); window.location.href='../../dashboard/mahasiswa/isi_krs.php';</script>";
      exit();
    }

    mysqli_query($conn, "
      INSERT INTO krs (mahasiswa_id, jadwal_id, tahun_ajaran_id, semester)
      VALUES ($mahasiswa_id, $jadwal_id, $tahun_ajaran_id, $semester)
    ");
  }
}

echo "<script>alert('KRS berhasil disimpan.'); window.location.href='../../dashboard/mahasiswa/krs_saya.php';</script>";
exit();
?>
