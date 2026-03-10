<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db/db_connect.php';

// ตรวจสอบว่าส่งข้อมูลมาแบบ POST หรือไม่ (มีคนกดปุ่ม Submit มาจริงๆ)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. กำหนดรหัสคนขาย (เนื่องจากเราเอาการล็อกอินออกไปแล้ว เลยบังคับให้เป็น user_id = 1 แทนชั่วคราวครับ)
    $user_id = $_SESSION['user']['id'] ?? 1; 

    // 2. รับค่าที่พิมพ์มาจากหน้าฟอร์ม
    $title = trim($_POST['title'] ?? '');
    $category_id = $_POST['cat_id'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $description = trim($_POST['description'] ?? '');

    try {
        // 3. นำข้อมูลไปบันทึกลงตารางรายการสินค้า (listings)
        $stmt = $pdo->prepare("
            INSERT INTO listings (user_id, category_id, title, description, price, condition_level, sell_status, visibility) 
            VALUES (:user_id, :cat_id, :title, :desc, :price, 'good', 'available', 'public')
        ");
        
        $stmt->execute([
            'user_id' => $user_id,
            'cat_id' => $category_id,
            'title' => $title,
            'desc' => $description,
            'price' => $price
        ]);
        
        // ดึง ID ของสินค้าชิ้นนี้ที่เพิ่งถูกสร้างขึ้นมา
        $listing_id = $pdo->lastInsertId();

        // 4. จัดการอัปโหลดรูปภาพ
        if (isset($_FILES['product_img']) && $_FILES['product_img']['error'] === UPLOAD_ERR_OK) {
            
            // สร้างโฟลเดอร์ uploads อัตโนมัติ (ถ้ายังไม่มี)
            $upload_dir = __DIR__ . '/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true); 
            }

            // สุ่มชื่อไฟล์ใหม่ไม่ให้ซ้ำกัน (ใช้เวลาปัจจุบัน + ชื่อเดิม)
            $file_tmp = $_FILES['product_img']['tmp_name'];
            $file_extension = pathinfo($_FILES['product_img']['name'], PATHINFO_EXTENSION);
            $new_file_name = time() . '_' . rand(1000,9999) . '.' . $file_extension;
            $file_path = 'uploads/' . $new_file_name;
            
            $mime_type = $_FILES['product_img']['type'];
            $size_bytes = $_FILES['product_img']['size'];

            // ทำการย้ายไฟล์รูปจากเครื่องเรา ไปเก็บไว้ในโฟลเดอร์ uploads ของโปรเจ็กต์
            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                
                // บันทึกชื่อและที่อยู่รูปลงในตาราง listing_images
                $img_stmt = $pdo->prepare("
                    INSERT INTO listing_images (listing_id, file_name, file_path, mime_type, size_bytes, is_primary) 
                    VALUES (?, ?, ?, ?, ?, 1)
                ");
                $img_stmt->execute([$listing_id, $new_file_name, $file_path, $mime_type, $size_bytes]);
            }
        }

        // 5. สำเร็จ! เด้งป๊อปอัปแจ้งเตือน แล้วพากลับไปดูสินค้าที่หน้าแรก
        echo "<script>
                alert('🚀 ลงประกาศขายสินค้าสำเร็จ!');
                window.location.href = '" . url("index.php") . "';
              </script>";
        exit;

    } catch (PDOException $e) {
        die("เกิดข้อผิดพลาดของ Database: " . $e->getMessage());
    }

} else {
    // ถ้าแอบพิมพ์ URL เข้ามาหน้านี้ตรงๆ ให้เตะกลับไปหน้าแรก
    header("Location: " . url("index.php"));
    exit;
}
?>