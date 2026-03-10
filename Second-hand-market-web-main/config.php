<?php
// ตั้งค่า Base URL ของเว็บไซต์ (แก้ให้ตรงกับชื่อโฟลเดอร์ใน XAMPP ของคุณ)
define('BASE_URL', 'http://localhost/Second-hand-market-web-main/');

// สร้างฟังก์ชัน url() สำหรับช่วยสร้างลิงก์ที่ถูกต้องเสมอ
if (!function_exists('url')) {
    function url($path = '') {
        return BASE_URL . ltrim($path, '/');
    }
}
?>