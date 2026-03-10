<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db/db_connect.php';

// รับค่า ID สินค้าจาก URL
$product_id = $_GET['id'] ?? 0;

// ดึงข้อมูลสินค้านั้นๆ
$stmt = $pdo->prepare("
    SELECT l.*, c.name AS category_name, u.display_name AS seller_name
    FROM listings l
    LEFT JOIN categories c ON l.category_id = c.id
    LEFT JOIN users u ON l.user_id = u.id
    WHERE l.id = :id AND l.visibility = 'public'
    LIMIT 1
");
$stmt->execute(['id' => $product_id]);
$product = $stmt->fetch();

// ถ้าไม่เจอสินค้า (หรือถูกลบไปแล้ว) ให้เด้งกลับหน้าแรก
if (!$product) {
    echo "<script>alert('ไม่พบสินค้านี้ หรือสินค้าถูกลบไปแล้ว'); window.location.href='index.php';</script>";
    exit;
}

// ดึงรูปภาพทั้งหมดของสินค้านี้มาแสดง (เรียงรูปหลักมาก่อน)
$img_stmt = $pdo->prepare("SELECT file_path, is_primary FROM listing_images WHERE listing_id = :id ORDER BY is_primary DESC, sort_order ASC");
$img_stmt->execute(['id' => $product_id]);
$images = $img_stmt->fetchAll();

// กรณีไม่มีรูปในฐานข้อมูลเลย ให้ใช้รูป Default 1 รูป
if (count($images) === 0) {
    $images[] = ['file_path' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80', 'is_primary' => 1];
}

// แปลงสถานะสินค้าเป็นภาษาไทย
$status_badge = "🟢 สถานะ: ว่าง (พร้อมขาย)";
if ($product['sell_status'] === 'reserved') $status_badge = "🟡 สถานะ: จองแล้ว";
if ($product['sell_status'] === 'sold') $status_badge = "🔴 สถานะ: ขายแล้ว";
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['title']) ?> - SecondHand Space</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&family=Prompt:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary-bg: #2C3E50; --accent: #E67E22; --accent-hover: #D35400; --light-bg: #F4F6F7; --text-dark: #333333; --text-light: #ffffff; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Kanit', sans-serif; background-color: var(--light-bg); color: var(--text-dark); }
        h1, h2, h3, h4, .logo { font-family: 'Prompt', sans-serif; }
        a { text-decoration: none; color: inherit; transition: 0.3s; }
        
        /* Navbar */
        .navbar { background-color: var(--primary-bg); color: var(--text-light); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar .logo-container { flex: 1; }
        .navbar .logo { font-size: 24px; font-weight: 600; color: var(--accent); letter-spacing: 1px; }
        .navbar .logo-sub { font-size: 12px; color: #BDC3C7; display: block; font-family: 'Kanit', sans-serif; }
        .navbar .nav-links { flex: 2; display: flex; justify-content: center; align-items: center; gap: 30px; }
        .navbar .nav-item { font-size: 15px; font-weight: 400; position: relative; padding-bottom: 5px; color: var(--text-light); cursor: pointer; }
        .navbar .nav-item::after { content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0; background-color: var(--accent); transition: width 0.3s ease; }
        .navbar .nav-item:hover::after { width: 100%; }
        .navbar .nav-item:hover { color: var(--accent); }
        .navbar .nav-actions { flex: 1; display: flex; justify-content: flex-end; align-items: center; gap: 20px; }
        .search-bar { background: rgba(255,255,255,0.9); border: none; padding: 8px 15px; border-radius: 20px; color: #333; outline: none; font-family: inherit; font-size: 14px; width: 200px; }
        .action-icon-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px; color: var(--text-light); text-decoration: none; background: transparent; border: none; cursor: pointer; transition: color 0.3s; font-family: 'Kanit', sans-serif; }
        .action-icon-btn:hover { color: var(--accent); }
        .action-icon-btn svg { width: 26px; height: 26px; }
        .action-icon-btn span { font-size: 13px; font-weight: 400; }
        
        .menu-action-container { position: relative; display: inline-block; }
        .hamburger-dropdown { display: none; position: absolute; right: 0; top: 130%; background-color: #ffffff; min-width: 290px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); padding: 20px; z-index: 1000; }
        .hamburger-dropdown.show { display: block; animation: fadeInRight 0.2s ease-in-out; }
        @keyframes fadeInRight { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .hm-item { display: flex; align-items: center; padding: 8px 0; text-decoration: none; color: #333 !important; transition: opacity 0.2s; }
        .hm-item:hover { opacity: 0.7; background-color: transparent; }
        .hm-icon { background-color: #EFEFEF; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: #333; }
        .hm-icon svg { width: 20px; height: 20px; }
        .hm-text { flex: 1; font-size: 16px !important; font-weight: 400; }
        .hm-divider { height: 1px; background-color: #E0E0E0; margin: 15px 0; position: relative; }

        /* สไตล์หน้ารายละเอียดสินค้า */
        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        .breadcrumb { margin-bottom: 20px; font-size: 14px; color: #7F8C8D; }
        .breadcrumb a { color: var(--primary-bg); text-decoration: none; }
        .breadcrumb a:hover { color: var(--accent); text-decoration: underline; }
        
        .product-wrapper { display: flex; gap: 40px; background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .product-gallery { flex: 1; width: 50%; }
        .main-image-container { width: 100%; height: 400px; border-radius: 12px; overflow: hidden; margin-bottom: 15px; border: 1px solid #eee; }
        .main-image { width: 100%; height: 100%; object-fit: contain; background-color: #f9f9f9; }
        .thumbnail-list { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 5px; }
        .thumbnail { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid transparent; transition: 0.3s; opacity: 0.6; }
        .thumbnail.active, .thumbnail:hover { border-color: var(--accent); opacity: 1; }

        .product-details { flex: 1; width: 50%; display: flex; flex-direction: column; }
        .tag-status { background-color: #27AE60; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; display: inline-block; width: fit-content; margin-bottom: 10px; }
        .product-title { font-size: 28px; color: var(--primary-bg); margin-bottom: 15px; line-height: 1.3; }
        .product-price { font-size: 36px; color: var(--accent); font-weight: 600; margin-bottom: 20px; font-family: 'Prompt', sans-serif; }
        
        .product-description { background-color: #F8F9F9; padding: 20px; border-radius: 12px; margin-bottom: 25px; font-size: 15px; color: #555; line-height: 1.6; flex-grow: 1; border: 1px solid #eee; white-space: pre-line; }
        .product-description h4 { margin-bottom: 10px; color: var(--primary-bg); border-bottom: 1px solid #ddd; padding-bottom: 5px; }

        .seller-card { display: flex; align-items: center; justify-content: space-between; padding: 15px; border: 1px solid #E5E8E8; border-radius: 12px; margin-bottom: 25px; }
        .seller-info-left { display: flex; align-items: center; gap: 15px; }
        .seller-avatar { width: 50px; height: 50px; background-color: var(--primary-bg); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold; }
        .seller-name { font-size: 16px; font-weight: 500; color: var(--primary-bg); }
        .seller-date { font-size: 12px; color: #7F8C8D; }
        
        .btn-contact { background-color: var(--primary-bg); color: white; padding: 15px; border: none; border-radius: 8px; font-size: 18px; font-weight: 500; cursor: pointer; transition: 0.3s; width: 100%; font-family: 'Prompt', sans-serif; display: flex; justify-content: center; align-items: center; gap: 10px; }
        .btn-contact:hover { background-color: #1A252F; transform: translateY(-2px); }

        @media (max-width: 768px) { .product-wrapper { flex-direction: column; } .product-gallery, .product-details { width: 100%; } }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo-container">
    <a href="index.php" class="logo" style="text-decoration: none; display: flex; align-items: center;">
        <img src="<?= url('img/logo.png') ?>" alt="SecondHand Logo" style="height: 40px; width: auto; object-fit: contain; display: block;">
    </a>
</div>
        <div class="nav-links">
            <a href="index.php" class="nav-item">หน้าหลัก</a>
            <a href="search.php" class="nav-item">รายการสินค้า</a>
            <a href="contact.php" class="nav-item">ติดต่อเรา</a>
        </div>
        <div class="nav-actions">
            <form action="search.php" method="GET" style="margin: 0; display: flex; align-items: center;">
                <input type="text" name="q" class="search-bar" placeholder="ค้นหาสินค้า...">
            </form>
            
            <a href="post_product.php" class="action-icon-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>ลงขาย</span>
            </a>

            <div class="menu-action-container">
                <button class="action-icon-btn" onclick="toggleMenu(event)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    <span>เมนู</span>
                </button>
                <div class="hamburger-dropdown" id="dropdownMenu">
                    <?php if(isset($_SESSION['user'])): ?>
                        <a href="#" class="hm-item">
                            <div class="hm-icon">👤</div>
                            <span class="hm-text"><?= htmlspecialchars($_SESSION['user']['display_name']) ?></span>
                        </a>
                        <div class="hm-divider"></div>
                        <a href="logout.php" class="hm-item">
                            <div class="hm-icon" style="color: #E74C3C;">🚪</div>
                            <span class="hm-text" style="color: #E74C3C;">ออกจากระบบ</span>
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="hm-item">
                            <div class="hm-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                            </div>
                            <span class="hm-text">เข้าสู่ระบบ/สมัครสมาชิก</span>
                        </a>
                    <?php endif; ?>
                    <div class="hm-divider with-dot"></div>
                    <a href="about.php" class="hm-item">
                        <div class="hm-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <span class="hm-text">เกี่ยวกับเรา</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">หน้าหลัก</a> &gt; 
            <a href="search.php?category=<?= $product['category_id'] ?>"><?= htmlspecialchars($product['category_name']) ?></a> &gt; 
            <span><?= htmlspecialchars($product['title']) ?></span>
        </div>

        <div class="product-wrapper">
            
            <div class="product-gallery">
                <div class="main-image-container">
                    <?php 
                        // ดึงรูปแรกออกมาเป็นรูปหลัก (ถ้าเป็นลิงก์เว็บอื่น ไม่ต้องครอบ url())
                        $first_img = $images[0]['file_path'];
                        $main_img = (strpos($first_img, 'http') === 0) ? $first_img : url($first_img); 
                    ?>
                    <img src="<?= htmlspecialchars($main_img) ?>" id="mainImage" class="main-image" alt="รูปหลัก">
                </div>
                
                <div class="thumbnail-list">
                    <?php foreach ($images as $index => $img): ?>
                        <?php 
                            $thumb_src = (strpos($img['file_path'], 'http') === 0) ? $img['file_path'] : url($img['file_path']); 
                        ?>
                        <img src="<?= htmlspecialchars($thumb_src) ?>" 
                             class="thumbnail <?= ($index === 0) ? 'active' : '' ?>" 
                             onclick="changeImage(this)">
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="product-details">
                <div class="tag-status"><?= $status_badge ?></div>
                <h1 class="product-title"><?= htmlspecialchars($product['title']) ?></h1>
                <div class="product-price">฿ <?= number_format($product['price'], 0) ?></div>
                
                <div class="product-description">
                    <h4>📝 รายละเอียดสินค้า</h4>
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                    
                    <?php if(!empty($product['location_text'])): ?>
                        <br><br><strong>📍 สถานที่รับสินค้า/พิกัด:</strong> <?= htmlspecialchars($product['location_text']) ?>
                    <?php endif; ?>
                </div>

                <div class="seller-card">
                    <div class="seller-info-left">
                        <div class="seller-avatar"><?= mb_substr($product['seller_name'], 0, 1, 'UTF-8') ?></div>
                        <div>
                            <div class="seller-name"><?= htmlspecialchars($product['seller_name']) ?></div>
                            <div class="seller-date">ลงประกาศเมื่อ: <?= date('d M Y H:i', strtotime($product['created_at'])) ?></div>
                        </div>
                    </div>
                </div>

                <button class="btn-contact" onclick="alert('ฟีเจอร์แชทกำลังจะเปิดให้บริการเร็วๆ นี้!');">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    ทักแชท / สนใจสั่งซื้อ
                </button>
            </div>

        </div>
    </div>

    <script>
        // เปลี่ยนรูปเมื่อคลิกรูปเล็ก
        function changeImage(element) {
            document.getElementById('mainImage').src = element.src;
            let thumbnails = document.getElementsByClassName('thumbnail');
            for(let i = 0; i < thumbnails.length; i++) {
                thumbnails[i].classList.remove('active');
            }
            element.classList.add('active');
        }

        // เมนู
        function toggleMenu(event) {
            event.stopPropagation();
            document.getElementById("dropdownMenu").classList.toggle("show");
        }
        window.onclick = function(event) {
            if (!event.target.closest('.menu-action-container')) {
                var dropdown = document.getElementById("dropdownMenu");
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>
</body>
</html>