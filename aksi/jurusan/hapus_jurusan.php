<?php
include '../../koneksi.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM jurusan WHERE id = $id");

header("Location: ../../dashboard/data_jurusan.php");
exit;
?>
