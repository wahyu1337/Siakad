<?php
session_start();
include '../../koneksi.php';

$mahasiswa_id = $_SESSION['mahasiswa_id'] ?? 0;

$tahunAjaran = mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE status_aktif = 1 LIMIT 1");
$dataTA = mysqli_fetch_assoc($tahunAjaran);
$idTahunAjaran = $dataTA['id'];

$jadwal = mysqli_query($conn, "
  SELECT 
    j.id AS jadwal_id,
    m.kode_mk, m.nama_mk, m.semester, m.sks,
    d.nama AS dosen,
    k.nama_kelas,
    j.hari, j.jam_mulai, j.jam_selesai
  FROM jadwal j
  JOIN matakuliah m ON j.matakuliah_id = m.id
  JOIN dosen d ON j.dosen_id = d.id
  JOIN kelas k ON j.kelas_id = k.id
  WHERE j.tahun_ajaran_id = $idTahunAjaran
");
?>

<form action="../../aksi/krs/simpan_krs.php" method="POST">
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Mata Kuliah</th>
        <th>Dosen</th>
        <th>Kelas</th>
        <th>Hari</th>
        <th>Jam</th>
        <th>SKS</th>
        <th>Semester</th>
        <th>Pilih</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; while ($row = mysqli_fetch_assoc($jadwal)) : ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['kode_mk'] ?></td>
        <td><?= $row['nama_mk'] ?></td>
        <td><?= $row['dosen'] ?></td>
        <td><?= $row['nama_kelas'] ?></td>
        <td><?= $row['hari'] ?></td>
        <td><?= substr($row['jam_mulai'], 0, 5) ?> - <?= substr($row['jam_selesai'], 0, 5) ?></td>
        <td><?= $row['sks'] ?></td>
        <td><?= $row['semester'] ?></td>
        <td><input type="checkbox" name="jadwal_id[]" value="<?= $row['jadwal_id'] ?>"></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <button type="submit" class="btn-submit" style="margin-top: 20px;">Simpan KRS</button>
</form>
