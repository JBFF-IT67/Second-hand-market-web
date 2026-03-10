<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db/db_connect.php';

// ดึงข้อมูลสินค้าล่าสุด 6 รายการจากฐานข้อมูล
$stmt = $pdo->prepare("
    SELECT l.id, l.title, l.price, l.created_at, 
           c.name AS category_name, 
           u.display_name AS seller_name,
           (SELECT file_path FROM listing_images WHERE listing_id = l.id AND is_primary = 1 LIMIT 1) AS image_path
    FROM listings l
    LEFT JOIN categories c ON l.category_id = c.id
    LEFT JOIN users u ON l.user_id = u.id
    WHERE l.visibility = 'public' AND l.sell_status = 'available'
    ORDER BY l.created_at DESC
    LIMIT 6
");
$stmt->execute();
$latest_products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecondHand Space - ตลาดสินค้ามือสองออนไลน์</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&family=Prompt:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root { --primary-bg: #2C3E50; --accent: #E67E22; --accent-hover: #D35400; --light-bg: #F4F6F7; --text-dark: #333333; --text-light: #ffffff; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Kanit', sans-serif; background-color: var(--light-bg); color: var(--text-dark); }
        h1, h2, h3, h4, .logo { font-family: 'Prompt', sans-serif; }
        a { text-decoration: none; color: inherit; transition: 0.3s; }

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

        /* หน้าหลัก */
        .hero { position: relative; height: 60vh; background-image: url('https://images.unsplash.com/photo-1555529771-835f59fc5efe?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; color: var(--text-light); }
        .hero::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(44, 62, 80, 0.7); z-index: 1; }
        .hero-content { position: relative; z-index: 2; max-width: 800px; padding: 20px; }
        .hero h1 { font-size: 48px; margin-bottom: 15px; font-weight: 600; }
        .hero p { font-size: 18px; margin-bottom: 30px; font-weight: 300; }
        .hero-buttons { display: flex; gap: 20px; justify-content: center; }
        .btn-primary { background-color: var(--accent); color: white; padding: 12px 30px; font-size: 16px; font-weight: 500; border: none; cursor: pointer; border-radius: 25px; transition: 0.3s; }
        .btn-primary:hover { background-color: var(--accent-hover); transform: translateY(-2px); }
        .btn-outline { background-color: transparent; border: 2px solid white; color: white; padding: 10px 30px; font-size: 16px; font-weight: 500; border-radius: 25px; cursor: pointer; transition: 0.3s; }
        .btn-outline:hover { background-color: white; color: var(--primary-bg); }

        .highlights { padding: 60px 40px; text-align: center; }
        .section-title { font-size: 28px; margin-bottom: 40px; color: var(--primary-bg); }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; max-width: 1200px; margin: 0 auto; text-align: left; }
        .product-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s; display: flex; flex-direction: column;}
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .product-img { width: 100%; height: 220px; object-fit: cover; }
        .product-info { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
        .category-tag { font-size: 12px; color: var(--accent); font-weight: 600; margin-bottom: 5px; display: inline-block; }
        .product-name { font-size: 16px; font-weight: 500; color: #333; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .price { font-size: 20px; font-weight: 600; color: var(--primary-bg); margin-bottom: auto; padding-bottom: 15px;}
        .seller-info { font-size: 12px; color: #7f8c8d; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #ecf0f1; padding-top: 10px; }
        .btn-view { background-color: var(--primary-bg); color: white; width: 100%; padding: 10px 0; border: none; font-size: 14px; font-weight: 500; cursor: pointer; border-radius: 5px; margin-top: 10px; transition: 0.3s; }
        .btn-view:hover { background-color: var(--accent); }
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
                    
                </div>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1>ส่งต่อของใช้คุณภาพดี ในราคาสบายกระเป๋า</h1>
            <p>ค้นหาสินค้ามือสองที่คุณตามหา หรือเปลี่ยนของไม่ได้ใช้ให้เป็นรายได้ง่ายๆ ที่นี่</p>
            <div class="hero-buttons">
                <a href="search.php"><button class="btn-primary">ค้นหาสินค้าเลย</button></a>
                <a href="post_product.php"><button class="btn-outline">ลงประกาศขาย</button></a>
            </div>
        </div>
    </section>

    <section class="highlights">
        <h2 class="section-title">✨ สินค้ามาใหม่ล่าสุด</h2>
        <div class="product-grid">
            
            <?php if (count($latest_products) > 0): ?>
                <?php foreach ($latest_products as $product): ?>
                    <div class="product-card">
                        <?php 
                            // เช็คว่ามีรูปใน Database ไหม ถ้าไม่มีให้แสดงรูปภาพแทน (Placeholder)
                            $img_src = $product['image_path'] ? url($product['image_path']) : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=400&q=80'; 
                        ?>
                        <img src="<?= htmlspecialchars($img_src) ?>" alt="Product" class="product-img">
                        <div class="product-info">
                            <span class="category-tag"><?= htmlspecialchars($product['category_name']) ?></span>
                            <div class="product-name"><?= htmlspecialchars($product['title']) ?></div>
                            <div class="price">฿ <?= number_format($product['price'], 0) ?></div>
                            <div class="seller-info">
                                <span>👤 <?= htmlspecialchars($product['seller_name']) ?></span>
                                <span><?= date('d/m/Y', strtotime($product['created_at'])) ?></span>
                            </div>
                            <a href="product_detail.php?id=<?= $product['id'] ?>" style="text-decoration:none;">
                                <button class="btn-view">ดูรายละเอียด</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; width: 100%; color: #7f8c8d;">ยังไม่มีสินค้าในขณะนี้ ลองลงขายสินค้าเป็นคนแรกสิ!</p>
            <?php endif; ?>
            </div>
    </section>

    <script>
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