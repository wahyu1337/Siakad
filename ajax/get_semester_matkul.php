<?php
include '../koneksi.php';

header('Content-Type: application/json');

$tahun_id = intval($_GET['tahun_ajaran_id'] ?? 0);
$response = ['semester' => '', 'matakuliah' => []];

if ($tahun_id > 0) {
  $ta = mysqli_fetch_assoc(mysqli_query($conn, "SELECT semester FROM tahun_ajaran WHERE id = $tahun_id"));
  if ($ta) {
    $semester = strtolower($ta['semester']) == 'ganjil' ? [1,3,5,7] : [2,4,6,8];

    session_start();
    $mahasiswa_id = $_SESSION['mahasiswa_id'] ?? 0;
    $mhs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT jurusan_id FROM mahasiswa WHERE id = $mahasiswa_id"));
    $jurusan_id = $mhs['jurusan_id'];

    $result = mysqli_query($conn, "SELECT id, nama_mk, semester, sks FROM matakuliah WHERE jurusan_id = $jurusan_id AND semester IN (".implode(',', $semester).") ORDER BY semester");
    while ($row = mysqli_fetch_assoc($result)) {
      $response['matakuliah'][] = $row;
    }
    $response['semester'] = $ta['semester'];
  }
}

echo json_encode($response);

?>