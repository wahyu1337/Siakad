<?php
session_start();
session_destroy();
header("Location: index.php"); // ← kembali ke beranda
exit;