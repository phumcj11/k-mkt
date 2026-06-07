<?php
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_login();
require_once dirname(__DIR__) . '/includes/layout.php';

$posts = db()->query('SELECT * FROM blog_posts ORDER BY published_at DESC, id DESC')->fetchAll();

admin_header('จัดการบทความ', 'blog/index.php');
?>

<div style="margin-bottom:20px;">
  <a href="edit.php" class="btn btn-primary">+ เพิ่มบทความ</a>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr><th>หัวข้อ</th><th>หมวด</th><th>สถานะ</th><th>วันที่</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($posts as $p): ?>
      <tr>
        <td><?= e($p['title']) ?><?= $p['is_featured'] ? ' <span class="badge badge-published">Featured</span>' : '' ?></td>
        <td><?= e($p['category']) ?></td>
        <td><span class="badge badge-<?= $p['status'] === 'published' ? 'published' : 'draft' ?>"><?= e($p['status']) ?></span></td>
        <td><?= e($p['published_at'] ? thai_date($p['published_at']) : '-') ?></td>
        <td style="white-space:nowrap;">
          <a href="edit.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-secondary">แก้ไข</a>
          <a href="delete.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ลบบทความนี้?')">ลบ</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php admin_footer(); ?>
