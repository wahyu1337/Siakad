<?php
include '../koneksi.php';

if (isset($_GET['tahun_ajaran_id'])) {
  $id = intval($_GET['tahun_ajaran_id']);

  // Ambil semester berdasarkan tahun ajaran
  $stmt = mysqli_prepare($conn, "SELECT semester FROM tahun_ajaran WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $semester);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  if ($semester) {
    // Tentukan semester genap/ganjil
    $semesterList = $semester === 'Ganjil' ? [1, 3, 5, 7] : [2, 4, 6, 8];
    $in = implode(',', array_map('intval', $semesterList));

    $query = "SELECT id, nama_mk, semester FROM matakuliah WHERE semester IN ($in) ORDER BY semester ASC, nama_mk ASC";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
      echo "<option value='{$row['id']}'>{$row['nama_mk']} (Semester {$row['semester']})</option>";
    }
  }
}
