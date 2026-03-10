<?php
// admin/login.php
session_start();
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/db/db_connect.php";

if (isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin') {
  header("Location: " . url("admin/index.php"));
  exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = strtolower(trim($_POST['email'] ?? ''));
  $pass  = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT id,email,password_hash,display_name,role,status FROM users WHERE email = :email LIMIT 1");
  $stmt->execute(['email' => $email]);
  $u = $stmt->fetch();

  if (!$u || !password_verify($pass, $u['password_hash'])) {
    $error = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
  } elseif ($u['status'] !== 'active') {
    $error = "บัญชีถูกระงับการใช้งาน";
  } elseif ($u['role'] !== 'admin') {
    $error = "บัญชีนี้ไม่ใช่แอดมิน";
  } else {
    session_regenerate_id(true);
    $_SESSION['user'] = [
      'id' => $u['id'],
      'email' => $u['email'],
      'display_name' => $u['display_name'],
      'role' => $u['role'],
    ];
    header("Location: " . url("admin/index.php"));
    exit;
  }
}
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    :root { --brand:#FFC107; --brand2:#ffda47; }
    body { background: linear-gradient(135deg, var(--brand2), #fff); min-height: 100vh; }
    .card { border: 0; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,.12); }
    .btn-brand { background: var(--brand); border: 0; }
    .btn-brand:hover { filter: brightness(.95); }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
  <div class="card p-4" style="max-width:420px; width:100%;">
    <h4 class="mb-1">Second-Hand Market</h4>
    <div class="text-muted mb-3">Admin Login</div>

    <form method="post" autocomplete="off">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="password" type="password" class="form-control" required>
      </div>
      <button class="btn btn-brand w-100 fw-semibold">เข้าสู่ระบบ</button>
      <a href="<?= url('index.php') ?>" class="btn btn-link w-100 mt-2">กลับหน้าเว็บ</a>
    </form>
  </div>

  <?php if ($error): ?>
  <script>
    Swal.fire({icon:'error', title:'เข้าสู่ระบบไม่สำเร็จ', text: <?= json_encode($error) ?>});
  </script>
  <?php endif; ?>
</body>
</html>
