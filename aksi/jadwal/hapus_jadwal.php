<?php
include '../../koneksi.php';
$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM jadwal WHERE id = $id");
header("Location: ../../dashboard/data_jadwal.php");
exit;
?>
