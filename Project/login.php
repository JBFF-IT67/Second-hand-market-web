<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - SecondHand Space</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&family=Prompt:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* --- ตัวแปรสีหลัก --- */
        :root { --primary-bg: #2C3E50; --accent: #E67E22; --accent-hover: #D35400; --light-bg: #F4F6F7; --text-dark: #333333; --text-light: #ffffff; --border-color: #BDC3C7; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Kanit', sans-serif; background-color: var(--light-bg); color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; }
        h1, h2, h3, h4, .logo { font-family: 'Prompt', sans-serif; }
        a { text-decoration: none; color: inherit; transition: 0.3s; }

        /* --- Navbar (แบบเดิม) --- */
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
        
        /* ปุ่มเมนู */
        .action-icon-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px; color: var(--text-light); text-decoration: none; background: transparent; border: none; cursor: pointer; transition: color 0.3s; font-family: 'Kanit', sans-serif; }
        .action-icon-btn:hover { color: var(--accent); }
        .action-icon-btn svg { width: 26px; height: 26px; }
        .action-icon-btn span { font-size: 13px; font-weight: 400; }
        
        .menu-action-container { position: relative; display: inline-block; }
        .hamburger-dropdown { display: none; position: absolute; right: 0; top: 130%; background-color: #ffffff; min-width: 280px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); padding: 15px; z-index: 1000; }
        .hamburger-dropdown.show { display: block; animation: fadeInRight 0.2s ease-in-out; }
        @keyframes fadeInRight { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .hm-item { display: flex; align-items: center; padding: 12px; border-radius: 14px; text-decoration: none; color: #333 !important; transition: background 0.2s; }
        .hm-item:hover { background-color: #F4F6F7; }
        .hm-icon { background-color: #EAECEE; width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: #333; }
        .hm-text { flex: 1; font-size: 15px !important; font-weight: 500; }
        .hm-divider { height: 1px; background-color: #E5E8E8; margin: 8px 10px; }

        /* --- สไตล์เฉพาะหน้า Login --- */
        .login-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .login-card { background: white; width: 100%; max-width: 450px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 40px; }
        
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h2 { font-size: 28px; color: var(--primary-bg); margin-bottom: 10px; }
        .login-header p { color: #7F8C8D; font-size: 15px; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--primary-bg); font-size: 14px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid var(--border-color); border-radius: 8px; font-family: 'Kanit', sans-serif; font-size: 15px; transition: 0.3s; }
        .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1); }
        
        .forgot-password { display: block; text-align: right; font-size: 13px; color: var(--accent); margin-top: 5px; }
        .forgot-password:hover { text-decoration: underline; }

        .btn-submit { width: 100%; background-color: var(--accent); color: white; padding: 12px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; font-family: 'Prompt', sans-serif; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-submit:hover { background-color: var(--accent-hover); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(230, 126, 34, 0.2); }

        .register-link { text-align: center; margin-top: 25px; font-size: 14px; color: #7F8C8D; }
        .register-link a { color: var(--primary-bg); font-weight: 600; }
        .register-link a:hover { color: var(--accent); text-decoration: underline; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo-container">
            <a href="index.php" class="logo">📦 SecondHand</a>
        </div>
        <div class="nav-links">
            <a href="index.php" class="nav-item">หน้าหลัก</a>
            <a href="categories.php" class="nav-item">หมวดหมู่ทั้งหมด</a>
        </div>
        <div class="nav-actions">
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
                        <div class="hm-icon">👤</div><span class="hm-text">เข้าสู่ระบบ/สมัครสมาชิก</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <h2>ยินดีต้อนรับกลับมา! 👋</h2>
                <p>เข้าสู่ระบบเพื่อลงขายหรือจัดการสินค้าของคุณ</p>
            </div>

            <form action="process_login.php" method="POST">
                
                <div class="form-group">
                    <label for="username">ชื่อผู้ใช้งาน หรือ อีเมล</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="กรอกชื่อผู้ใช้งาน หรือ อีเมล" required>
                </div>

                <div class="form-group">
                    <label for="password">รหัสผ่าน</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="กรอกรหัสผ่านของคุณ" required>
                    <a href="#" class="forgot-password">ลืมรหัสผ่านใช่หรือไม่?</a>
                </div>

                <button type="submit" class="btn-submit">เข้าสู่ระบบ</button>

            </form>

            <div class="register-link">
                ยังไม่มีบัญชีใช่ไหม? <a href="register.php">สมัครสมาชิกเลย</a>
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