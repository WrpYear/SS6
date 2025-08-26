<?php
// เริ่มต้น session ก่อน
session_start();

// ทำลาย session ทั้งหมด
session_destroy();

// กลับไปหน้า login หรือหน้าแรก
header("Location: login.php");
exit;
?>
