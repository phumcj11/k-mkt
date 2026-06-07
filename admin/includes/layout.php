<?php
function admin_base(): string
{
    return (str_contains($_SERVER['PHP_SELF'], '/blog/') || str_contains($_SERVER['PHP_SELF'], '/cases/')) ? '../' : '';
}

function admin_header(string $title, string $active = ''): void
{
    $base = admin_base();
    $user = current_user();
    $nav = [
        'dashboard.php' => 'แดชบอร์ด',
        'blog/index.php' => 'บทความ',
        'cases/index.php' => 'ผลงาน',
        'settings.php' => 'ตั้งค่าติดต่อ',
        'submissions.php' => 'ฟอร์มลูกค้า',
    ];
    $flash = get_flash();
    ?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title) ?> | กาญจน์ตลาด Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $base ?>assets/admin.css">
</head>
<body>
<div class="admin-wrap">
  <aside class="admin-sidebar">
    <div class="brand">กาญจน์ตลาด</div>
    <nav class="admin-nav">
      <?php foreach ($nav as $href => $label):
        $isActive = $active === $href;
      ?>
      <a href="<?= $base . $href ?>" class="<?= $isActive ? 'active' : '' ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
      <a href="<?= $base ?>logout.php">ออกจากระบบ</a>
      <a href="<?= $base ?>../index.html" target="_blank" style="margin-top:16px;border-top:1px solid rgba(255,255,255,0.08);padding-top:16px;">ดูเว็บไซต์ →</a>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="admin-header">
      <h1><?= e($title) ?></h1>
      <span style="font-size:0.85rem;color:var(--admin-muted);"><?= e($user['username'] ?? '') ?></span>
    </div>
    <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>
    <?php
}

function admin_footer(): void
{
    ?>
  </main>
</div>
</body>
</html>
    <?php
}
