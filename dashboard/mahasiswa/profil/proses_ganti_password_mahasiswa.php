<?php
session_start();
include '../../../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../../../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$password_lama = $_POST['password_lama'] ?? '';
$password_baru = $_POST['password_baru'] ?? '';
$konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

// Ambil password lama dari database
$query = "SELECT password FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data || !password_verify($password_lama, $data['password'])) {
    $_SESSION['error'] = 'Password lama salah.';
    header('Location: ganti_password_mahasiswa.php');
    exit;
}

if (strlen($password_baru) < 6) {
    $_SESSION['error'] = 'Password baru minimal 6 karakter.';
    header('Location: ganti_password_mahasiswa.php');
    exit;
}

if ($password_baru !== $konfirmasi_password) {
    $_SESSION['error'] = 'Konfirmasi password tidak cocok.';
    header('Location: ganti_password_mahasiswa.php');
    exit;
}

// Update password baru
$password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
$update = "UPDATE users SET password = ? WHERE id = ?";
$stmt = $conn->prepare($update);
$stmt->bind_param('si', $password_hash, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['success'] = 'Password berhasil diperbarui.';
} else {
    $_SESSION['error'] = 'Gagal memperbarui password.';
}

header('Location: ganti_password_mahasiswa.php');
exit;
?>