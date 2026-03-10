<!-- แก้ URL โยงไปหน้าอื่นที่ Line 29 -->
<?php
session_start();
require_once "db/db_connect.php"; //ถ้าErrorลองลบ db/ (ใส่เพราะถ้าไม่มีจะไม่เชื่อมฐานข้อมูลให้)

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    /* ================= LOGIN ================= */

    if($_POST['action'] == "login"){

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if($user){

            if(password_verify($password,$user['password_hash'])){

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['display_name'];

                header("Location: dashboard.php"); //แก้ URL ที่ต้องการเชื่อม
                exit();

            }else{
                $message = "รหัสผ่านไม่ถูกต้อง";
            }

        }else{
            $message = "ไม่พบผู้ใช้";
        }
    }

    /* ================= REGISTER ================= */

    if($_POST['action'] == "register"){

        $display_name = $_POST['username'];
        $email = $_POST['email'];
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // เช็ค email ซ้ำ
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if($stmt->rowCount() > 0){

            $message = "อีเมลนี้ถูกใช้แล้ว";

        }else{

            $stmt = $pdo->prepare(
                "INSERT INTO users (display_name,email,password_hash,role,status)
                VALUES (?,?,?,'user','active')"
            );

            if($stmt->execute([$display_name,$email,$password_hash])){
                $message = "สมัครสมาชิกสำเร็จ";
            }
        }
    }
}
?>
<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2e3f52",
                        "background-light": "#f6f7f7",
                        "background-dark": "#16191c",
                    },
                    fontFamily: {
                        "display": ["Inter"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
<title>Login - SecondHand</title>
</head>
<script>

function toggleRegister(){

let form = document.getElementById("registerForm");

if(form.classList.contains("hidden")){
form.classList.remove("hidden");
}else{
form.classList.add("hidden");
}

}

</script>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen flex flex-col">
<header class="bg-primary px-4 md:px-20 lg:px-40 py-4 flex items-center justify-between shadow-md">
<div class="flex items-center gap-3 text-white">
<img src="img/second-hand-market-logo.png" 
     alt="SecondHand Logo" 
     class="h-10 w-10 rounded-full object-cover bg-white p-1 border border-white/30 shadow-md">
<h1 class="text-xl font-bold tracking-tight">SecondHand</h1>
</div>
<div class="flex items-center gap-4">

<!-- ใส่ช่องทางติดต่อตรงนี้ -->
<a href="https://example.com" target="_blank" 
class="hidden sm:block text-white text-sm font-semibold px-4 py-2 hover:bg-white/10 rounded-lg transition-colors">
ศูนย์บริการ
</a>
</div>
</header>
<main class="flex-grow flex items-center justify-center p-6 relative overflow-hidden">
<div class="absolute top-20 left-10 opacity-10 pointer-events-none rotate-12 hidden lg:block">
<span class="material-symbols-outlined text-9xl text-primary">apparel</span>
</div>
<div class="absolute bottom-20 right-10 opacity-10 pointer-events-none -rotate-12 hidden lg:block">
<span class="material-symbols-outlined text-9xl text-primary">chair</span>
</div>
<div class="absolute top-1/2 right-20 opacity-5 pointer-events-none hidden lg:block">
<span class="material-symbols-outlined text-[12rem] text-primary">watch</span>
</div>
<div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden relative z-10">
<div class="p-8 relative">
<div class="text-center mb-10">
    <?php if($message!=""){ ?>
<p class="text-red-500 text-sm text-center mb-4">
<?php echo $message; ?>
</p>
<?php } ?>
<h2 class="text-3xl font-black text-primary dark:text-slate-100 mb-2">ยินดีต้อนรับกลับ</h2>
<p class="text-slate-500 dark:text-slate-400">มองหาอัญมณีลับตอนนี้</p>
</div>
    <!-- แบบฟอร์ม Login -->
<form method="POST" class="space-y-6">
<input type="hidden" name="action" value="login">
<div class="flex flex-col gap-2">
<label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="email">ที่อยู่อีเมล</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">mail</span>
    <!-- inputของemail -->
<input name="email"
class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-slate-100"
id="email"
placeholder="name@example.com"
type="email"/>
</div>
</div>
<div class="flex flex-col gap-2">
<div class="flex justify-between items-center ml-1">
<label class="text-sm font-semibold text-slate-700 dark:text-slate-300" for="password">รหัสผ่าน</label>
</div>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">lock</span>
    <!-- inputของPassword -->
<input name="password" id="password" placeholder="••••••••" type="password"
class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-slate-100"/>
</div>
<div class="flex items-center gap-2 px-1">
<input class="rounded border-slate-300 text-primary focus:ring-primary" id="remember" type="checkbox"/>
<label class="text-xs text-slate-600 dark:text-slate-400 font-medium" for="remember">จดจำฉัน</label>
</div>
<button class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-lg shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 group" type="submit">
<span>ล็อกอิน</span>
<span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
</form>
    <!-- แบบฟอร์ม Register -->
<form method="POST" id="registerForm"
class="space-y-4 hidden mt-6 absolute top-0 left-0 w-full h-full bg-white dark:bg-slate-800 p-8">

    <input type="hidden" name="action" value="register">

    <h2 class="text-3xl font-black text-primary mb-6 text-center">
        สมัครสมาชิก
    </h2>
    <!-- inputของUsername -->
    <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="reg_username">ชื่อผู้ใช้</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">person</span>
            <input name="username" id="reg_username" type="text"
                placeholder="username"
                class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-slate-100"/>
        </div>
    </div>
    <!-- inputของemail -->
    <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="reg_email">ที่อยู่อีเมล</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">mail</span>
            <input name="email" id="reg_email" type="email"
                placeholder="name@example.com"
                class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-slate-100"/>
        </div>
    </div>
    <!-- inputของPassword -->
    <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="reg_password">รหัสผ่าน</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">lock</span>
            <input name="password" id="reg_password" type="password"
                placeholder="••••••••"
                class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-slate-100"/>
        </div>
    </div>

    <button type="submit"
        class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-lg shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 group">
        <span>สมัครสมาชิก</span>
        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
    </button>

    <button type="button"
        onclick="toggleRegister()"
        class="w-full text-sm text-slate-500 dark:text-slate-400 hover:text-primary transition-colors mt-2 flex items-center justify-center gap-1">
        <span class="material-symbols-outlined text-base">arrow_back</span>
        กลับไปหน้า Login
    </button>

</form>
</div>
<div class="bg-slate-50 dark:bg-slate-900/50 p-6 text-center border-t border-slate-200 dark:border-slate-700">
<p class="text-sm text-slate-600 dark:text-slate-400">
                    ยังไม่มีบัญชี?
                    <a class="text-primary font-bold hover:underline ml-1 cursor-pointer"
onclick="toggleRegister()">
สร้างบัญชี
</a>
</p>
</div>
</div>
</main>
<footer class="py-8 px-6 text-center text-slate-400 text-xs">
<div class="flex justify-center gap-6 mb-4">
</div>
<p>© 2024 SecondHand. All rights reserved.</p>
</footer>
</body></html>