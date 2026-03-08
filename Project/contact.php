<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดต่อสอบถาม - SecondHand Space</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&family=Prompt:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root { --primary-bg: #2C3E50; --accent: #E67E22; --accent-hover: #D35400; --light-bg: #F4F6F7; --text-dark: #333333; --text-light: #ffffff; --border-color: #BDC3C7; }
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

        /* --- สไตล์หน้า Contact Us --- */
        .page-header { background-color: white; padding: 40px 20px; text-align: center; border-bottom: 1px solid #E5E8E8; }
        .page-header h1 { font-size: 32px; color: var(--primary-bg); margin-bottom: 10px; }
        .page-header p { color: #7F8C8D; font-size: 16px; }

        .container { max-width: 1000px; margin: 50px auto; padding: 0 20px; }
        
        .contact-wrapper { display: flex; gap: 40px; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        
        /* ฝั่งซ้าย: ข้อมูลการติดต่อ */
        .contact-info { flex: 1; background-color: var(--primary-bg); color: white; padding: 40px; }
        .contact-info h3 { font-size: 24px; margin-bottom: 20px; color: var(--accent); }
        .contact-info p { margin-bottom: 30px; font-weight: 300; line-height: 1.6; color: #E5E8E8; }
        
        .info-item { display: flex; align-items: flex-start; gap: 15px; margin-bottom: 20px; }
        .info-icon { background: rgba(255,255,255,0.1); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .info-detail h4 { font-size: 16px; margin-bottom: 5px; font-weight: 500; }
        .info-detail p { margin-bottom: 0; font-size: 14px; color: #BDC3C7; }

        /* ฝั่งขวา: ฟอร์มติดต่อ */
        .contact-form { flex: 1.5; padding: 40px; }
        .contact-form h3 { font-size: 24px; margin-bottom: 20px; color: var(--primary-bg); }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #555; font-size: 14px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid var(--border-color); border-radius: 8px; font-family: 'Kanit', sans-serif; font-size: 15px; transition: border-color 0.3s; background-color: #FAFAFA; }
        .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1); background-color: white; }
        textarea.form-control { resize: vertical; min-height: 120px; }
        
        .btn-submit { background-color: var(--accent); color: white; padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; font-family: 'Prompt', sans-serif; cursor: pointer; transition: 0.3s; margin-top: 10px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-submit:hover { background-color: var(--accent-hover); transform: translateY(-2px); }

        @media (max-width: 768px) {
            .contact-wrapper { flex-direction: column; }
            .contact-info, .contact-form { padding: 30px 20px; }
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

    <div class="page-header">
        <h1>ติดต่อสอบถาม</h1>
        <p>มีข้อสงสัย พบปัญหาการใช้งาน หรือต้องการแนะนำติชม? เราพร้อมรับฟังคุณเสมอ!</p>
    </div>

    <div class="container">
        <div class="contact-wrapper">
            
            <div class="contact-info">
                <h3>ช่องทางการติดต่อ</h3>
                <p>ทีมงานของเราพร้อมให้บริการและจะรีบตอบกลับคุณภายใน 24 ชั่วโมงทำการ (จันทร์ - ศุกร์)</p>
                
                <div class="info-item">
                    <div class="info-icon">📍</div>
                    <div class="info-detail">
                        <h4>ที่อยู่สำนักงาน</h4>
                        <p>123 อาคารซอฟต์แวร์ปาร์ค ชั้น 5<br>ถนนแจ้งวัฒนะ ปากเกร็ด นนทบุรี 11120</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">📞</div>
                    <div class="info-detail">
                        <h4>เบอร์โทรศัพท์</h4>
                        <p>02-123-4567</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">✉️</div>
                    <div class="info-detail">
                        <h4>อีเมล</h4>
                        <p>support@secondhandspace.com</p>
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <h3>ส่งข้อความถึงเรา</h3>
                <form action="#" method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="name">ชื่อ - นามสกุล</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="กรอกชื่อของคุณ" required>
                        </div>
                        <div class="form-group">
                            <label for="email">อีเมล</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="example@email.com" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">หัวข้อที่ต้องการติดต่อ</label>
                        <select id="subject" name="subject" class="form-control" required>
                            <option value="">-- เลือกหัวข้อ --</option>
                            <option value="support">แจ้งปัญหาการใช้งานเว็บไซต์</option>
                            <option value="report">รายงานผู้ใช้ / สินค้าไม่เหมาะสม</option>
                            <option value="business">ติดต่อธุรกิจ / โฆษณา</option>
                            <option value="other">เรื่องอื่นๆ</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">รายละเอียด / ข้อความ</label>
                        <textarea id="message" name="message" class="form-control" placeholder="พิมพ์ข้อความที่คุณต้องการแจ้งให้เราทราบ..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        ส่งข้อความ
                    </button>
                </form>
            </div>

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