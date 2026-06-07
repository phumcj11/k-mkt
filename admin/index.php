<?php
require_once dirname(__DIR__) . '/includes/auth.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$dbError = !db_available();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($dbError) {
        $error = 'Database ยังไม่พร้อม — รัน deploy.bat บนเครื่อง local เพื่อตั้งค่า pcj_kmkt';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        try {
            if (login_user($username, $password)) {
                redirect('dashboard.php');
            }
            $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
        } catch (Throwable $e) {
            $error = 'เชื่อมต่อ Database ไม่ได้ — ตรวจสอบ deploy.secrets และรัน deploy.bat';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เข้าสู่ระบบ | กาญจน์ตลาด Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<div class="login-page">
  <div class="login-box">
    <h1>กาญจน์ตลาด</h1>
    <p>ระบบหลังบ้านจัดการเว็บไซต์</p>
    <?php if ($dbError): ?><div class="alert alert-error">⚠️ Database ยังไม่เชื่อมต่อ (pcj_kmkt)<br>รัน <strong>scripts\init-deploy-secrets.bat</strong> แล้วกด <strong>deploy.bat</strong></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <div class="form-group">
        <label>ชื่อผู้ใช้</label>
        <input type="text" name="username" required autofocus>
      </div>
      <div class="form-group">
        <label>รหัสผ่าน</label>
        <input type="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px;">เข้าสู่ระบบ</button>
    </form>
  </div>
</div>
</body>
</html>
