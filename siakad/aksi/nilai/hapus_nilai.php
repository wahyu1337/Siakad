<?php
include '../../koneksi.php';
$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM nilai WHERE id = $id");
header("Location: ../../dashboard/data_nilai.php");
exit;
?>
