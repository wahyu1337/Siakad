<?php
include '../koneksi.php';

if (isset($_GET['tahun_ajaran_id'])) {
  $tahun_ajaran_id = intval($_GET['tahun_ajaran_id']);

  // Ambil semester dari tahun ajaran
  $ta = mysqli_fetch_assoc(mysqli_query($conn, "SELECT semester FROM tahun_ajaran WHERE id = $tahun_ajaran_id"));
  if (!$ta) {
    echo json_encode([]);
    exit;
  }

  // Mapping semester Ganjil/Genap ke angka ganjil/genap
  $semester_type = $ta['semester']; // Ganjil / Genap

  $filter = ($semester_type === 'Ganjil') ? 'MOD(semester,2) = 1' : 'MOD(semester,2) = 0';

  // Ambil data mata kuliah
  $query = mysqli_query($conn, "SELECT id, nama_mk FROM matakuliah WHERE $filter ORDER BY semester ASC");

  $result = [];
  while ($row = mysqli_fetch_assoc($query)) {
    $result[] = $row;
  }

  echo json_encode($result);
}
?>
