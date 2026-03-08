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
        /* --- ตัวแปรสีหลัก --- */
        :root { --primary-bg: #2C3E50; --accent: #E67E22; --accent-hover: #D35400; --light-bg: #F4F6F7; --text-dark: #333333; --text-light: #ffffff; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Kanit', sans-serif; background-color: var(--light-bg); color: var(--text-dark); }
        h1, h2, h3, h4, .logo { font-family: 'Prompt', sans-serif; }
        a { text-decoration: none; color: inherit; transition: 0.3s; }

        /* --- Navbar --- */
        .navbar { background-color: var(--primary-bg); color: var(--text-light); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar .logo-container { flex: 1; }
        .navbar .logo { font-size: 24px; font-weight: 600; color: var(--accent); letter-spacing: 1px; }
        .navbar .logo-sub { font-size: 12px; color: #BDC3C7; display: block; font-family: 'Kanit', sans-serif; }
        .navbar .nav-links { flex: 2; display: flex; justify-content: center; align-items: center; gap: 30px; }
        .navbar .nav-item { font-size: 15px; font-weight: 400; position: relative; padding-bottom: 5px; color: var(--text-light); cursor: pointer; }
        .navbar .nav-item::after { content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0; background-color: var(--accent); transition: width 0.3s ease; }
        .navbar .nav-item:hover::after { width: 100%; }
        .navbar .nav-item:hover { color: var(--accent); }
        
        .dropdown { position: relative; display: inline-block; }
        .dropdown .arrow { font-size: 10px; margin-left: 5px; transition: transform 0.3s; display: inline-block; }
        .dropdown:hover .arrow { transform: rotate(180deg); }
        .dropdown-content { display: none; position: absolute; background-color: #ffffff; min-width: 180px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1); z-index: 101; border-radius: 8px; top: 100%; left: 50%; transform: translateX(-50%); padding: 10px 0; margin-top: 10px; border: 1px solid #eee; }
        .dropdown-content a { color: #333 !important; padding: 10px 20px; display: block; font-size: 14px !important; transition: background 0.3s, padding-left 0.3s; }
        .dropdown-content a::after { display: none; }
        .dropdown-content a:hover { background-color: #F4F6F7; color: var(--accent) !important; padding-left: 25px; }
        .dropdown:hover .dropdown-content { display: block; animation: fadeIn 0.3s ease-in-out; }

        .navbar .nav-actions { flex: 1; display: flex; justify-content: flex-end; align-items: center; gap: 20px; }
        .search-bar { background: rgba(255,255,255,0.9); border: none; padding: 8px 15px; border-radius: 20px; color: #333; outline: none; font-family: inherit; font-size: 14px; width: 200px; }
        
        .action-icon-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px; color: var(--text-light); text-decoration: none; background: transparent; border: none; cursor: pointer; transition: color 0.3s; font-family: 'Kanit', sans-serif; }
        .action-icon-btn:hover { color: var(--accent); }
        .action-icon-btn svg { width: 26px; height: 26px; }
        .action-icon-btn span { font-size: 13px; font-weight: 400; }
        
        /* สไตล์ใหม่ของเมนูแฮมเบอร์เกอร์ตามภาพ */
        .menu-action-container { position: relative; display: inline-block; }
        .hamburger-dropdown { display: none; position: absolute; right: 0; top: 130%; background-color: #ffffff; min-width: 290px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); padding: 20px; z-index: 1000; }
        .hamburger-dropdown.show { display: block; animation: fadeInRight 0.2s ease-in-out; }
        @keyframes fadeInRight { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .hm-item { display: flex; align-items: center; padding: 8px 0; text-decoration: none; color: #333 !important; transition: opacity 0.2s; }
        .hm-item::after { display: none !important; }
        .hm-item:hover { opacity: 0.7; background-color: transparent; }
        
        .hm-icon { background-color: #EFEFEF; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: #333; }
        .hm-icon svg { width: 20px; height: 20px; }
        .hm-text { flex: 1; font-size: 16px !important; font-weight: 400; }
        .hm-value { font-size: 14px; color: #555; }
        
        .hm-divider { height: 1px; background-color: #E0E0E0; margin: 15px 0; position: relative; }
        .hm-divider.with-dot { display: flex; justify-content: center; }
        .hm-divider.with-dot::after { content: ''; position: absolute; top: -1.5px; width: 4px; height: 4px; background-color: #E74C3C; border-radius: 50%; }

        /* --- เค้าโครงหน้าค้นหา --- */
        .search-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: flex; gap: 30px; }
        
        /* 1. แถบตัวกรองด้านซ้าย (Sidebar Filter) */
        .filter-sidebar { width: 280px; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eee; height: fit-content; }
        .filter-title { font-size: 18px; color: var(--primary-bg); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #F4F6F7; padding-bottom: 10px; }
        
        .filter-group { margin-bottom: 25px; }
        .filter-label { font-weight: 500; color: #333; margin-bottom: 10px; display: block; }
        
        /* ช่องค้นหาคำค้น */
        .filter-input-text { width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Kanit', sans-serif; font-size: 14px; outline: none; margin-bottom: 15px; }
        .filter-input-text:focus { border-color: var(--accent); }

        /* ตัวกรองหมวดหมู่ (Radio/Checkbox) */
        .radio-group { display: flex; flex-direction: column; gap: 10px; }
        .radio-label { display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555; cursor: pointer; }
        .radio-label input[type="radio"], .radio-label input[type="checkbox"] { accent-color: var(--accent); cursor: pointer; width: 16px; height: 16px; }

        /* ตัวกรองราคา (Price Range) */
        .price-inputs { display: flex; gap: 10px; align-items: center; }
        .price-inputs input { width: 100%; padding: 8px 10px; border: 1px solid #ddd; border-radius: 6px; font-family: 'Kanit', sans-serif; outline: none; }
        .price-inputs span { color: #7F8C8D; }

        .btn-filter { width: 100%; background-color: var(--primary-bg); color: white; padding: 12px; border: none; border-radius: 8px; font-size: 16px; font-weight: 500; cursor: pointer; transition: 0.3s; font-family: 'Prompt', sans-serif; }
        .btn-filter:hover { background-color: var(--accent); }

        /* 2. ผลลัพธ์การค้นหาด้านขวา (Search Results) */
        .results-area { flex: 1; }
        .results-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .results-count { font-size: 18px; color: #555; }
        .sort-select { padding: 8px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Kanit', sans-serif; outline: none; cursor: pointer; }

        /* กริดสินค้า (นำมาจากหน้า index) */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
        .product-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: 0.3s; border: 1px solid #eee; display: flex; flex-direction: column; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); border-color: var(--accent); }
        .product-img { width: 100%; height: 200px; object-fit: cover; }
        .product-info { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; }
        .category-tag { font-size: 11px; color: var(--accent); font-weight: 600; margin-bottom: 5px; display: inline-block; background: #FFF3E0; padding: 3px 8px; border-radius: 4px; width: fit-content; }
        .product-name { font-size: 15px; font-weight: 500; color: #333; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; }
        .price { font-size: 18px; font-weight: 600; color: var(--primary-bg); margin-bottom: auto; }
        .seller-info { font-size: 11px; color: #7f8c8d; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f0f0f0; padding-top: 10px; margin-top: 15px; }

        /* รองรับมือถือ */
        @media (max-width: 768px) {
            .search-container { flex-direction: column; }
            .filter-sidebar { width: 100%; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo-container">
            <a href="index.php" class="logo">📦 SecondHand</a>
            <span class="logo-sub">ตลาดของมือสองออนไลน์</span>
        </div>

        <div class="nav-links">
            <a href="index.php" class="nav-item">หน้าหลัก</a>
            <a href="search.php" class="nav-item">รายการสินค้า</a>
            <div class="dropdown">
                <span class="nav-item dropbtn">หมวดหมู่ทั้งหมด <span class="arrow">▼</span></span>
                <div class="dropdown-content">
                    <a href="categories.php">เสื้อ</a>
                    <a href="categories.php">กางเกง</a>
                    <a href="categories.php">รองเท้า</a>
                    <a href="categories.php">กระเป๋า</a>
                    <a href="categories.php">เสื้อกันหนาว</a>
                    <a href="categories.php">โน๊ตบุ๊ค</a>
                    <a href="categories.php">มือถือ</a>
                    <a href="categories.php">เมาส์</a>
                    <a href="categories.php">คีย์บอร์ด</a>
                </div>
            </div>
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
                    
                    <a href="login.php" class="hm-item">
                        <div class="hm-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        </div>
                        <span class="hm-text">เข้าสู่ระบบ/สมัครสมาชิก</span>
                    </a>
                    
                    <div class="hm-divider with-dot"></div>
                    
                    <a href="#" class="hm-item">
                        <div class="hm-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>
                        </div>
                        <span class="hm-text">โหมดสี</span>
                        <span class="hm-value">สว่าง</span>
                    </a>
                    
                    <a href="#" class="hm-item">
                        <div class="hm-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
                        </div>
                        <span class="hm-text">ภาษา</span>
                        <span class="hm-value">ไทย</span>
                    </a>

                    <div class="hm-divider"></div>
                    
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
                <div class="filter-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    ตัวกรองการค้นหา
                </div>

                <div class="filter-group">
                    <label class="filter-label">ค้นหาคำค้น (Keyword)</label>
                    <input type="text" name="q" class="filter-input-text" placeholder="เช่น iPhone, เสื้อแจ็คเก็ต...">
                </div>

                <div class="filter-group">
                    <label class="filter-label">หมวดหมู่สินค้า</label>
                    <div class="radio-group">
                        <label class="radio-label"><input type="radio" name="category" value="all" checked> ทั้งหมด</label>
                        <label class="radio-label"><input type="radio" name="category" value="1"> อุปกรณ์ไอที / มือถือ</label>
                        <label class="radio-label"><input type="radio" name="category" value="2"> เสื้อผ้าแฟชั่น</label>
                        <label class="radio-label"><input type="radio" name="category" value="3"> กระเป๋า & เครื่องประดับ</label>
                        <label class="radio-label"><input type="radio" name="category" value="4"> รองเท้า</label>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">ช่วงราคา (บาท)</label>
                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="ต่ำสุด" min="0">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="สูงสุด" min="0">
                    </div>
                </div>

                <button type="submit" class="btn-filter">ค้นหาสินค้า</button>
            </form>
        </aside>

        <main class="results-area">
            <div class="results-header">
                <div class="results-count">พบสินค้า <b>4</b> รายการ</div>
                <div>
                    <select class="sort-select" name="sort">
                        <option value="newest">อัปเดตล่าสุด</option>
                        <option value="price_asc">ราคา: ต่ำ - สูง</option>
                        <option value="price_desc">ราคา: สูง - ต่ำ</option>
                    </select>
                </div>
            </div>

            <div class="product-grid">
                <a href="product_detail.php" class="product-card">
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=400&q=80" alt="iPhone" class="product-img">
                    <div class="product-info">
                        <span class="category-tag">อุปกรณ์ไอที</span>
                        <div class="product-name">iPhone 13 มือสอง สภาพ 95% เครื่องศูนย์ไทย</div>
                        <div class="price">฿ 15,900</div>
                        <div class="seller-info"><span>👤 JaiDee_Shop</span><span>1 ชม. ที่แล้ว</span></div>
                    </div>
                </a>

                <a href="product_detail.php" class="product-card">
                    <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=400&q=80" alt="Shoes" class="product-img">
                    <div class="product-info">
                        <span class="category-tag">รองเท้า</span>
                        <div class="product-name">รองเท้าผ้าใบ Nike Air Max 97 ไซส์ 42 ของแท้</div>
                        <div class="price">฿ 1,200</div>
                        <div class="seller-info"><span>👤 SneakerHead</span><span>8 ต.ค.</span></div>
                    </div>
                </a>
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