<?php
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_login();
require_once dirname(__DIR__) . '/includes/layout.php';

$cases = db()->query('SELECT * FROM case_studies ORDER BY sort_order ASC, id DESC')->fetchAll();

admin_header('จัดการผลงาน', 'cases/index.php');
?>

<div style="margin-bottom:20px;">
  <a href="edit.php" class="btn btn-primary">+ เพิ่มผลงาน</a>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr><th>ชื่อโครงการ</th><th>อุตสาหกรรม</th><th>สถานะ</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($cases as $c): ?>
      <tr>
        <td><?= e($c['title']) ?> — <?= e($c['subtitle']) ?></td>
        <td><?= e($c['industry_tag']) ?></td>
        <td><span class="badge badge-<?= $c['status'] === 'published' ? 'published' : 'draft' ?>"><?= e($c['status']) ?></span></td>
        <td style="white-space:nowrap;">
          <a href="edit.php?id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-secondary">แก้ไข</a>
          <a href="delete.php?id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ลบผลงานนี้?')">ลบ</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php admin_footer(); ?>
