<?php
include '../../koneksi.php';

$id = $_GET['id'];

// Cek apakah dosen masih digunakan di tabel lain jika perlu (opsional)

mysqli_query($conn, "DELETE FROM dosen WHERE id = $id");

header("Location: ../../dashboard/data_dosen.php");
exit;
?>
