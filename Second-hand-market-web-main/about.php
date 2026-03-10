<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db/db_connect.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เกี่ยวกับเรา - SecondHand Space</title>
    
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

        /* --- สไตล์หน้า About Us --- */
        .page-header { background-color: var(--primary-bg); color: white; padding: 60px 20px; text-align: center; }
        .page-header h1 { font-size: 36px; color: var(--accent); margin-bottom: 15px; }
        .page-header p { font-size: 18px; font-weight: 300; max-width: 600px; margin: 0 auto; color: #E5E8E8; }

        .container { max-width: 1000px; margin: 50px auto; padding: 0 20px; }
        
        .about-section { display: flex; align-items: center; gap: 40px; margin-bottom: 60px; background: white; padding: 40px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .about-section.reverse { flex-direction: row-reverse; }
        .about-text { flex: 1; }
        .about-text h2 { font-size: 28px; color: var(--primary-bg); margin-bottom: 20px; border-bottom: 3px solid var(--accent); display: inline-block; padding-bottom: 5px; }
        .about-text p { font-size: 16px; color: #555; line-height: 1.8; margin-bottom: 15px; }
        
        .about-image { flex: 1; text-align: center; }
        .about-image img { width: 100%; max-width: 400px; border-radius: 12px; object-fit: cover; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 40px; }
        .feature-card { background: white; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: 0.3s; border-bottom: 4px solid transparent; }
        .feature-card:hover { transform: translateY(-5px); border-bottom-color: var(--accent); }
        .feature-icon { font-size: 40px; margin-bottom: 15px; display: inline-block; }
        .feature-title { font-size: 20px; color: var(--primary-bg); margin-bottom: 10px; font-weight: 600; }
        .feature-desc { font-size: 14px; color: #7F8C8D; }

        @media (max-width: 768px) {
            .about-section, .about-section.reverse { flex-direction: column; text-align: center; padding: 20px; }
        }
    </style>
</head>
<body>

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

    <div class="page-header">
        <h1>เกี่ยวกับ SecondHand Space</h1>
        <p>ชุมชนสำหรับการซื้อ-ขายของมือสองออนไลน์ ที่เชื่อมโยงผู้คนเข้าด้วยกันผ่านสิ่งของที่มีคุณค่า</p>
    </div>

    <div class="container">
        
        <div class="about-section">
            <div class="about-text">
                <h2>เริ่มต้นจากความตั้งใจ</h2>
                <p>เราเชื่อว่าของทุกชิ้นมีคุณค่าในตัวมันเอง เสื้อผ้าที่คุณไม่ได้ใส่แล้ว อาจเป็นตัวโปรดของใครอีกคน โทรศัพท์มือถือที่คุณอยากเปลี่ยน อาจเป็นเครื่องแรกที่ตอบโจทย์การทำงานของนักเรียนสักคน</p>
                <p>SecondHand Space ถูกสร้างขึ้นมาเพื่อเป็นพื้นที่ตรงกลาง ให้คุณสามารถส่งต่อของไม่ได้ใช้ เปลี่ยนเป็นรายได้ และในขณะเดียวกันก็ช่วยลดปัญหาขยะโลก (Zero Waste) ด้วยการนำสิ่งของกลับมาหมุนเวียนใช้อย่างคุ้มค่า</p>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Second hand shopping">
            </div>
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 28px; color: var(--primary-bg);">ทำไมต้องเลือกเรา?</h2>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3 class="feature-title">ใช้งานง่าย & ปลอดภัย</h3>
                <p class="feature-desc">ระบบของเราออกแบบมาให้ใช้งานง่าย ไม่ซับซ้อน สามารถลงประกาศขายได้ฟรีภายใน 1 นาที</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💬</div>
                <h3 class="feature-title">ติดต่อกันโดยตรง</h3>
                <p class="feature-desc">ผู้ซื้อและผู้ขายสามารถพูดคุย ต่อรองราคา หรือนัดรับสินค้ากันได้โดยตรง ไม่ผ่านคนกลาง</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌍</div>
                <h3 class="feature-title">รักษ์โลก ลดขยะ</h3>
                <p class="feature-desc">ทุกการซื้อขายของคุณ คือส่วนหนึ่งในการช่วยลดการใช้ทรัพยากรใหม่และลดขยะให้กับโลกใบนี้</p>
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