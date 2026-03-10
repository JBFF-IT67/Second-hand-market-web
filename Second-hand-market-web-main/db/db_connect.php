<?php
$host = "localhost";
$dbname = "secondhand_market";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // แจ้ง error แบบ exception
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch เป็น array แบบ associative
            PDO::ATTR_EMULATE_PREPARES => false               // ปิด emulate เพื่อความปลอดภัย
        ]
    );

    // echo "Connected successfully";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}