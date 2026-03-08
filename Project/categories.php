<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หมวดหมู่สินค้า - SecondHand Space</title>
    
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

        .dropdown { position: relative; display: inline-block; }
        .dropdown .arrow { font-size: 10px; margin-left: 5px; transition: transform 0.3s; display: inline-block; }
        .dropdown:hover .arrow { transform: rotate(180deg); }
        .dropdown-content { display: none; position: absolute; background-color: #ffffff; min-width: 180px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1); z-index: 101; border-radius: 8px; top: 100%; left: 50%; transform: translateX(-50%); padding: 10px 0; margin-top: 10px; border: 1px solid #eee; }
        .dropdown-content a { color: #333 !important; padding: 10px 20px; display: block; font-size: 14px !important; transition: background 0.3s, padding-left 0.3s; }
        .dropdown-content a::after { display: none; }
        .dropdown-content a:hover { background-color: #F4F6F7; color: var(--accent) !important; padding-left: 25px; }
        .dropdown:hover .dropdown-content { display: block; animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translate(-50%, 10px); } to { opacity: 1; transform: translate(-50%, 0); } }

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

        /* สไตล์เฉพาะหน้าหมวดหมู่ */
        .page-header { background-color: white; padding: 40px 20px; text-align: center; border-bottom: 1px solid #E5E8E8; }
        .page-header h1 { font-size: 32px; color: var(--primary-bg); margin-bottom: 10px; }
        .page-header p { color: #7F8C8D; font-size: 16px; }

        .container { max-width: 1200px; margin: 50px auto; padding: 0 20px; }
        .category-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; }
        .category-card { background-color: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; transition: all 0.3s ease; display: block; border: 1px solid transparent; }
        .category-card:hover { transform: translateY(-8px); box-shadow: 0 12px 20px rgba(0,0,0,0.1); border-color: var(--accent); }
        .category-img { width: 100%; height: 180px; object-fit: cover; border-bottom: 3px solid var(--accent); }
        .category-info { padding: 20px; }
        .category-title { font-size: 20px; font-weight: 600; color: var(--primary-bg); margin-bottom: 5px; }
        .category-count { font-size: 14px; color: #7F8C8D; }
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
    <div class="page-header">
        <h1>หมวดหมู่สินค้าทั้งหมด</h1>
        <p>เลือกดูสินค้ามือสองคุณภาพดี แยกตามหมวดหมู่ที่คุณสนใจ</p>
    </div>

    <div class="container">
        <div class="category-grid">
            <a href="#" class="category-card">
                <img src="https://images.unsplash.com/photo-1526738549149-8e07eca6c147?auto=format&fit=crop&w=400&q=80" alt="ไอที" class="category-img">
                <div class="category-info">
                    <div class="category-title">คอมพิวเตอร์ & ไอที</div>
                    <div class="category-count">1,240 รายการ</div>
                </div>
            </a>
            <a href="#" class="category-card">
                <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=400&q=80" alt="เสื้อผ้า" class="category-img">
                <div class="category-info">
                    <div class="category-title">เสื้อผ้าแฟชั่น</div>
                    <div class="category-count">3,500 รายการ</div>
                </div>
            </a>
            <a href="#" class="category-card">
                <img src="https://images.unsplash.com/photo-1544457070-4cd773b4d71e?auto=format&fit=crop&w=400&q=80" alt="กระเป๋า" class="category-img">
                <div class="category-info">
                    <div class="category-title">กระเป๋า & เครื่องประดับ</div>
                    <div class="category-count">2,100 รายการ</div>
                </div>
            </a>
        </div>
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