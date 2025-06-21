<?php
include '../../koneksi.php';
$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM tahun_ajaran WHERE id = $id");
header("Location: ../../dashboard/data_tahun_ajaran.php");
exit;
?>
