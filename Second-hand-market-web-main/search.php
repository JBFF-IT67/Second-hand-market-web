<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db/db_connect.php';

// --- 1. ดึงหมวดหมู่ทั้งหมดมาแสดงที่แถบกรองด้านซ้าย ---
$cat_stmt = $pdo->query("SELECT id, name FROM categories WHERE is_active = 1 ORDER BY id ASC");
$db_categories = $cat_stmt->fetchAll();

// --- 2. รับค่าที่ส่งมาจากการค้นหา (Filters) ---
$q = $_GET['q'] ?? '';
$category_filter = $_GET['category'] ?? 'all';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

// --- 3. สร้างคำสั่ง SQL แบบไดนามิก ---
$sql = "SELECT l.id, l.title, l.price, l.created_at, c.name AS category_name, u.display_name AS seller_name,
        (SELECT file_path FROM listing_images WHERE listing_id = l.id AND is_primary = 1 LIMIT 1) AS image_path
        FROM listings l
        LEFT JOIN categories c ON l.category_id = c.id
        LEFT JOIN users u ON l.user_id = u.id
        WHERE l.visibility = 'public' AND l.sell_status = 'available'";

$params = [];

// กรองตามคำค้นหา
if ($q !== '') {
    $sql .= " AND (l.title LIKE :q1 OR l.description LIKE :q2)";
    $params['q1'] = "%$q%";
    $params['q2'] = "%$q%";
}
// กรองตามหมวดหมู่
if ($category_filter !== 'all' && $category_filter !== '') {
    $sql .= " AND l.category_id = :cat";
    $params['cat'] = $category_filter;
}
// กรองราคาต่ำสุด
if ($min_price !== '') {
    $sql .= " AND l.price >= :min_p";
    $params['min_p'] = (float)$min_price;
}
// กรองราคาสูงสุด
if ($max_price !== '') {
    $sql .= " AND l.price <= :max_p";
    $params['max_p'] = (float)$max_price;
}

// การเรียงลำดับ
if ($sort === 'price_asc') {
    $sql .= " ORDER BY l.price ASC";
} elseif ($sort === 'price_desc') {
    $sql .= " ORDER BY l.price DESC";
} else {
    $sql .= " ORDER BY l.created_at DESC"; // newest (ค่าเริ่มต้น)
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params); 
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ค้นหาสินค้า - SecondHand Space</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&family=Prompt:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* ตัวแปรสีหลัก */
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
        .hm-value { font-size: 14px; color: #555; }
        .hm-divider { height: 1px; background-color: #E0E0E0; margin: 15px 0; position: relative; }
        .hm-divider.with-dot { display: flex; justify-content: center; }
        .hm-divider.with-dot::after { content: ''; position: absolute; top: -1.5px; width: 4px; height: 4px; background-color: #E74C3C; border-radius: 50%; }

        /* เค้าโครงหน้าค้นหา */
        .search-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: flex; gap: 30px; }
        .filter-sidebar { width: 280px; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eee; height: fit-content; position: sticky; top: 90px; }
        .filter-title { font-size: 18px; color: var(--primary-bg); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #F4F6F7; padding-bottom: 10px; }
        .filter-group { margin-bottom: 25px; }
        .filter-label { font-weight: 500; color: #333; margin-bottom: 10px; display: block; }
        .filter-input-text { width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Kanit', sans-serif; font-size: 14px; outline: none; margin-bottom: 15px; }
        .filter-input-text:focus { border-color: var(--accent); }
        .radio-group { display: flex; flex-direction: column; gap: 10px; }
        .radio-label { display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555; cursor: pointer; }
        .radio-label input[type="radio"] { accent-color: var(--accent); cursor: pointer; width: 16px; height: 16px; }
        .price-inputs { display: flex; gap: 10px; align-items: center; }
        .price-inputs input { width: 100%; padding: 8px 10px; border: 1px solid #ddd; border-radius: 6px; font-family: 'Kanit', sans-serif; outline: none; }
        .btn-filter { width: 100%; background-color: var(--primary-bg); color: white; padding: 12px; border: none; border-radius: 8px; font-size: 16px; font-weight: 500; cursor: pointer; transition: 0.3s; font-family: 'Prompt', sans-serif; }
        .btn-filter:hover { background-color: var(--accent); }

        .results-area { flex: 1; }
        .results-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .results-count { font-size: 18px; color: #555; }
        .sort-select { padding: 8px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Kanit', sans-serif; outline: none; cursor: pointer; }

        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
        .product-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: 0.3s; border: 1px solid #eee; display: flex; flex-direction: column; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); border-color: var(--accent); }
        .product-img { width: 100%; height: 200px; object-fit: cover; }
        .product-info { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; }
        .category-tag { font-size: 11px; color: var(--accent); font-weight: 600; margin-bottom: 5px; display: inline-block; background: #FFF3E0; padding: 3px 8px; border-radius: 4px; width: fit-content; }
        .product-name { font-size: 15px; font-weight: 500; color: #333; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; }
        .price { font-size: 18px; font-weight: 600; color: var(--primary-bg); margin-bottom: auto; }
        .seller-info { font-size: 11px; color: #7f8c8d; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f0f0f0; padding-top: 10px; margin-top: 15px; }

        @media (max-width: 768px) { .search-container { flex-direction: column; } .filter-sidebar { width: 100%; position: static; } }
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
                <input type="hidden" name="category" value="<?= htmlspecialchars($category_filter) ?>">
                <input type="hidden" name="min_price" value="<?= htmlspecialchars($min_price) ?>">
                <input type="hidden" name="max_price" value="<?= htmlspecialchars($max_price) ?>">
                <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                <input type="text" name="q" class="search-bar" value="<?= htmlspecialchars($q) ?>" placeholder="ค้นหาสินค้า...">
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

    <div class="search-container">
        
        <aside class="filter-sidebar">
            <form action="search.php" method="GET">
                <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">

                <div class="filter-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    ตัวกรองการค้นหา
                </div>

                <div class="filter-group">
                    <label class="filter-label">ค้นหาคำค้น (Keyword)</label>
                    <input type="text" name="q" class="filter-input-text" value="<?= htmlspecialchars($q) ?>" placeholder="เช่น iPhone, เสื้อแจ็คเก็ต...">
                </div>

                <div class="filter-group">
                    <label class="filter-label">หมวดหมู่สินค้า</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="category" value="all" <?= ($category_filter === 'all' || $category_filter === '') ? 'checked' : '' ?>> ทั้งหมด
                        </label>
                        <?php foreach($db_categories as $cat): ?>
                            <label class="radio-label">
                                <input type="radio" name="category" value="<?= $cat['id'] ?>" <?= ($category_filter == $cat['id']) ? 'checked' : '' ?>> 
                                <?= htmlspecialchars($cat['name']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">ช่วงราคา (บาท)</label>
                    <div class="price-inputs">
                        <input type="number" name="min_price" value="<?= htmlspecialchars($min_price) ?>" placeholder="ต่ำสุด" min="0">
                        <span>-</span>
                        <input type="number" name="max_price" value="<?= htmlspecialchars($max_price) ?>" placeholder="สูงสุด" min="0">
                    </div>
                </div>

                <button type="submit" class="btn-filter">กรองข้อมูล</button>
            </form>
        </aside>

        <main class="results-area">
            <div class="results-header">
                <div class="results-count">พบสินค้า <b><?= count($products) ?></b> รายการ</div>
                <div>
                    <form action="search.php" method="GET" id="sortForm">
                        <input type="hidden" name="q" value="<?= htmlspecialchars($q) ?>">
                        <input type="hidden" name="category" value="<?= htmlspecialchars($category_filter) ?>">
                        <input type="hidden" name="min_price" value="<?= htmlspecialchars($min_price) ?>">
                        <input type="hidden" name="max_price" value="<?= htmlspecialchars($max_price) ?>">
                        
                        <select class="sort-select" name="sort" onchange="document.getElementById('sortForm').submit();">
                            <option value="newest" <?= ($sort === 'newest') ? 'selected' : '' ?>>อัปเดตล่าสุด</option>
                            <option value="price_asc" <?= ($sort === 'price_asc') ? 'selected' : '' ?>>ราคา: ต่ำ - สูง</option>
                            <option value="price_desc" <?= ($sort === 'price_desc') ? 'selected' : '' ?>>ราคา: สูง - ต่ำ</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="product-grid">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <a href="product_detail.php?id=<?= $product['id'] ?>" class="product-card">
                            <?php 
                                // เช็คว่ามีรูปใน Database ไหม ถ้าไม่มีให้แสดงรูปภาพแทน (Placeholder)
                                $img_src = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=400&q=80';
                                if ($product['image_path']) {
                                    $img_src = (strpos($product['image_path'], 'http') === 0) ? $product['image_path'] : url($product['image_path']);
                                }
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
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align:center; grid-column: 1 / -1; color: #7f8c8d; padding: 40px 0;">
                        ไม่พบสินค้าที่คุณค้นหา ลองเปลี่ยนคำค้นหรือตัวกรองหมวดหมู่ใหม่ดูนะครับ
                    </p>
                <?php endif; ?>
            </div>
        </main>

    </div>

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