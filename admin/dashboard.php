<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/layout.php';

$blogCount = db()->query("SELECT COUNT(*) FROM blog_posts WHERE status='published'")->fetchColumn();
$caseCount = db()->query("SELECT COUNT(*) FROM case_studies WHERE status='published'")->fetchColumn();
$unreadCount = db()->query("SELECT COUNT(*) FROM form_submissions WHERE is_read=0")->fetchColumn();
$totalForms = db()->query("SELECT COUNT(*) FROM form_submissions")->fetchColumn();

$recentForms = db()->query("SELECT * FROM form_submissions ORDER BY created_at DESC LIMIT 5")->fetchAll();

admin_header('แดชบอร์ด', 'dashboard.php');
?>

<div class="stats-grid">
  <div class="stat-box"><div class="num"><?= (int)$blogCount ?></div><div class="lbl">บทความเผยแพร่</div></div>
  <div class="stat-box"><div class="num"><?= (int)$caseCount ?></div><div class="lbl">ผลงานเผยแพร่</div></div>
  <div class="stat-box"><div class="num"><?= (int)$unreadCount ?></div><div class="lbl">ฟอร์มยังไม่อ่าน</div></div>
  <div class="stat-box"><div class="num"><?= (int)$totalForms ?></div><div class="lbl">ฟอร์มทั้งหมด</div></div>
</div>

<div class="admin-card">
  <h2 style="font-size:1.1rem;margin-bottom:16px;">ฟอร์มล่าสุด</h2>
  <?php if (empty($recentForms)): ?>
  <p style="color:var(--admin-muted);">ยังไม่มีฟอร์ม</p>
  <?php else: ?>
  <table class="admin-table">
    <thead><tr><th>ประเภท</th><th>ชื่อ/ธุรกิจ</th><th>เบอร์</th><th>วันที่</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($recentForms as $f): ?>
      <tr>
        <td><?= e($f['form_type']) ?></td>
        <td><?= e($f['name'] ?: $f['business_name']) ?></td>
        <td><?= e($f['phone']) ?></td>
        <td><?= e(date('d/m/Y H:i', strtotime($f['created_at']))) ?></td>
        <td><a href="submissions.php?id=<?= (int)$f['id'] ?>" class="btn btn-sm btn-secondary">ดู</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<div style="display:flex;gap:12px;flex-wrap:wrap;">
  <a href="blog/edit.php" class="btn btn-primary">+ เพิ่มบทความ</a>
  <a href="cases/edit.php" class="btn btn-primary">+ เพิ่มผลงาน</a>
  <a href="settings.php" class="btn btn-secondary">ตั้งค่าติดต่อ</a>
</div>

<?php admin_footer(); ?>
