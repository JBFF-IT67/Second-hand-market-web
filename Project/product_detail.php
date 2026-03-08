<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดสินค้า - SecondHand Space</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&family=Prompt:wght@400;600&display=swap" rel="stylesheet">

    <style>
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
        .navbar .nav-actions { flex: 1; display: flex; justify-content: flex-end; align-items: center; gap: 20px; }
        .search-bar { background: rgba(255,255,255,0.9); border: none; padding: 8px 15px; border-radius: 20px; color: #333; outline: none; font-family: inherit; font-size: 14px; width: 200px; }
        .action-icon-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px; color: var(--text-light); text-decoration: none; background: transparent; border: none; cursor: pointer; transition: color 0.3s; font-family: 'Kanit', sans-serif; }
        .action-icon-btn:hover { color: var(--accent); }
        .action-icon-btn svg { width: 26px; height: 26px; }
        .action-icon-btn span { font-size: 13px; font-weight: 400; }
        
        .dropdown { position: relative; display: inline-block; }
        .dropdown .arrow { font-size: 10px; margin-left: 5px; transition: transform 0.3s; display: inline-block; }
        .dropdown:hover .arrow { transform: rotate(180deg); }
        .dropdown-content { display: none; position: absolute; background-color: #ffffff; min-width: 180px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1); z-index: 101; border-radius: 8px; top: 100%; left: 50%; transform: translateX(-50%); padding: 10px 0; margin-top: 10px; border: 1px solid #eee; }
        .dropdown-content a { color: #333 !important; padding: 10px 20px; display: block; font-size: 14px !important; transition: background 0.3s, padding-left 0.3s; }
        .dropdown-content a::after { display: none; }
        .dropdown-content a:hover { background-color: #F4F6F7; color: var(--accent) !important; padding-left: 25px; }
        .dropdown:hover .dropdown-content { display: block; animation: fadeIn 0.3s ease-in-out; }

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

        /* --- สไตล์หน้ารายละเอียดสินค้า --- */
        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        
        /* ขนมปังนำทาง (Breadcrumb) */
        .breadcrumb { margin-bottom: 20px; font-size: 14px; color: #7F8C8D; }
        .breadcrumb a { color: var(--primary-bg); text-decoration: none; }
        .breadcrumb a:hover { color: var(--accent); text-decoration: underline; }

        .product-wrapper { display: flex; gap: 40px; background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        /* ฝั่งซ้าย: รูปภาพ (Gallery) */
        .product-gallery { flex: 1; width: 50%; }
        .main-image-container { width: 100%; height: 400px; border-radius: 12px; overflow: hidden; margin-bottom: 15px; border: 1px solid #eee; }
        .main-image { width: 100%; height: 100%; object-fit: contain; background-color: #f9f9f9; }
        
        .thumbnail-list { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 5px; }
        .thumbnail { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid transparent; transition: 0.3s; opacity: 0.6; }
        .thumbnail.active, .thumbnail:hover { border-color: var(--accent); opacity: 1; }

        /* ฝั่งขวา: รายละเอียด */
        .product-details { flex: 1; width: 50%; display: flex; flex-direction: column; }
        .tag-status { background-color: #27AE60; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; display: inline-block; width: fit-content; margin-bottom: 10px; }
        .product-title { font-size: 28px; color: var(--primary-bg); margin-bottom: 15px; line-height: 1.3; }
        .product-price { font-size: 36px; color: var(--accent); font-weight: 600; margin-bottom: 20px; font-family: 'Prompt', sans-serif; }
        
        .product-description { background-color: #F8F9F9; padding: 20px; border-radius: 12px; margin-bottom: 25px; font-size: 15px; color: #555; line-height: 1.6; flex-grow: 1; border: 1px solid #eee; }
        .product-description h4 { margin-bottom: 10px; color: var(--primary-bg); border-bottom: 1px solid #ddd; padding-bottom: 5px; }

        /* กล่องข้อมูลผู้ขาย */
        .seller-card { display: flex; align-items: center; justify-content: space-between; padding: 15px; border: 1px solid #E5E8E8; border-radius: 12px; margin-bottom: 25px; }
        .seller-info-left { display: flex; align-items: center; gap: 15px; }
        .seller-avatar { width: 50px; height: 50px; background-color: var(--primary-bg); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold; }
        .seller-name { font-size: 16px; font-weight: 500; color: var(--primary-bg); }
        .seller-date { font-size: 12px; color: #7F8C8D; }
        
        .btn-contact { background-color: var(--primary-bg); color: white; padding: 15px; border: none; border-radius: 8px; font-size: 18px; font-weight: 500; cursor: pointer; transition: 0.3s; width: 100%; font-family: 'Prompt', sans-serif; display: flex; justify-content: center; align-items: center; gap: 10px; }
        .btn-contact:hover { background-color: #1A252F; transform: translateY(-2px); }

        @media (max-width: 768px) {
            .product-wrapper { flex-direction: column; }
            .product-gallery, .product-details { width: 100%; }
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
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">หน้าหลัก</a> &gt; 
            <a href="categories.php">อุปกรณ์ไอที / มือถือ</a> &gt; 
            <span>iPhone 13 มือสอง สภาพ 95%</span>
        </div>

        <div class="product-wrapper">
            
            <div class="product-gallery">
                <div class="main-image-container">
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=800&q=80" id="mainImage" class="main-image" alt="รูปหลัก">
                </div>
                
                <div class="thumbnail-list">
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=200&q=80" class="thumbnail active" onclick="changeImage(this)">
                    <img src="https://images.unsplash.com/photo-1523206489230-c012c64b2b48?auto=format&fit=crop&w=200&q=80" class="thumbnail" onclick="changeImage(this)">
                    <img src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?auto=format&fit=crop&w=200&q=80" class="thumbnail" onclick="changeImage(this)">
                </div>
            </div>

            <div class="product-details">
                <div class="tag-status">🟢 สถานะ: ว่าง (พร้อมขาย)</div>
                <h1 class="product-title">iPhone 13 มือสอง สภาพ 95% เครื่องศูนย์ไทย</h1>
                <div class="product-price">฿ 15,900</div>
                
                <div class="product-description">
                    <h4>📝 รายละเอียดสินค้า</h4>
                    <p>ขาย iPhone 13 ความจุ 128GB สี Midnight สภาพสวยมาก ไม่มีรอยตกหล่น แบตเตอรี่สุขภาพ 89% ใช้งานได้ปกติทุกฟังก์ชัน สแกนหน้าแม่นยำ</p>
                    <br>
                    <p>- อุปกรณ์: ครบกล่อง (สายชาร์จแท้ยังไม่ได้แกะใช้)</p>
                    <p>- ประกัน: หมดแล้ว (ประกันใจให้ 7 วัน)</p>
                    <p>- เหตุผลที่ขาย: เปลี่ยนไปใช้รุ่นใหม่</p>
                </div>

                <div class="seller-card">
                    <div class="seller-info-left">
                        <div class="seller-avatar">J</div>
                        <div>
                            <div class="seller-name">JaiDee_Shop</div>
                            <div class="seller-date">ลงประกาศเมื่อ: 10 ต.ค. 2023</div>
                        </div>
                    </div>
                </div>

                <button class="btn-contact">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    ทักแชท / สนใจสั่งซื้อ
                </button>
            </div>

        </div>
    </div>

    <script>
        // ระบบเปลี่ยนรูปภาพ (Gallery)
        function changeImage(element) {
            // เปลี่ยน src ของรูปหลักให้เป็นของรูปที่คลิก
            document.getElementById('mainImage').src = element.src;
            
            // ลบ class 'active' ออกจากรูปย่อยทั้งหมด
            let thumbnails = document.getElementsByClassName('thumbnail');
            for(let i = 0; i < thumbnails.length; i++) {
                thumbnails[i].classList.remove('active');
            }
            
            // เพิ่ม class 'active' ให้รูปที่เพิ่งคลิก
            element.classList.add('active');
        }

        // ระบบคลิกเปิด-ปิดเมนู
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