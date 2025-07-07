<?php
include '../../koneksi.php';

$nim = $_POST['nim'];
$id_matkul = $_POST['id_matkul'];
$id_kelas = $_POST['id_kelas'];
$id_tahun_ajaran = $_POST['id_tahun_ajaran'];
$semester = $_POST['semester'];

$query = "INSERT INTO krs (nim, id_matakuliah, id_kelas, id_tahun_ajaran, semester) 
          VALUES ('$nim', '$id_matkul', '$id_kelas', '$id_tahun_ajaran', '$semester')";

if (mysqli_query($conn, $query)) {
    header("Location: ../../dashboard/mahasiswa/krs_saya.php");
} else {
    echo "Gagal menambahkan KRS: " . mysqli_error($conn);
}
?>