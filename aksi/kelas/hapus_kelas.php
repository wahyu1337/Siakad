<?php
include '../../koneksi.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM kelas WHERE id = $id");

header("Location: ../../dashboard/data_kelas.php");
exit;
?>
