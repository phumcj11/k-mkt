<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/layout.php';

if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    $stmt = db()->prepare('UPDATE form_submissions SET is_read=1 WHERE id=?');
    $stmt->execute([(int)$_GET['read']]);
    flash('success', 'ทำเครื่องหมายอ่านแล้ว');
    redirect('submissions.php');
}

$detail = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = db()->prepare('SELECT * FROM form_submissions WHERE id=?');
    $stmt->execute([(int)$_GET['id']]);
    $detail = $stmt->fetch();
    if ($detail && !$detail['is_read']) {
        db()->prepare('UPDATE form_submissions SET is_read=1 WHERE id=?')->execute([(int)$detail['id']]);
    }
}

$submissions = db()->query('SELECT * FROM form_submissions ORDER BY created_at DESC')->fetchAll();

admin_header('ฟอร์มจากลูกค้า', 'submissions.php');
?>

<?php if ($detail): ?>
<div class="admin-card">
  <h2 style="font-size:1.1rem;margin-bottom:16px;">รายละเอียดฟอร์ม #<?= (int)$detail['id'] ?></h2>
  <table class="admin-table">
    <tr><th style="width:160px;">ประเภท</th><td><?= e($detail['form_type']) ?></td></tr>
    <tr><th>ชื่อ</th><td><?= e($detail['name']) ?></td></tr>
    <tr><th>ธุรกิจ</th><td><?= e($detail['business_name']) ?></td></tr>
    <tr><th>เบอร์</th><td><a href="tel:<?= e($detail['phone']) ?>"><?= e($detail['phone']) ?></a></td></tr>
    <tr><th>อีเมล</th><td><?= e($detail['email']) ?></td></tr>
    <tr><th>ประเภทธุรกิจ</th><td><?= e($detail['business_type']) ?></td></tr>
    <tr><th>บริการที่สนใจ</th><td><?= e($detail['service_interest']) ?></td></tr>
    <tr><th>ข้อความ</th><td><?= nl2br(e($detail['message'])) ?></td></tr>
    <?php if ($detail['extra_data']):
      $extra = json_decode($detail['extra_data'], true);
      foreach ($extra as $k => $v): ?>
    <tr><th><?= e($k) ?></th><td><?= e($v) ?></td></tr>
    <?php endforeach; endif; ?>
    <tr><th>วันที่ส่ง</th><td><?= e(date('d/m/Y H:i', strtotime($detail['created_at']))) ?></td></tr>
  </table>
  <a href="submissions.php" class="btn btn-secondary" style="margin-top:16px;">← กลับ</a>
</div>
<?php else: ?>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr><th>สถานะ</th><th>ประเภท</th><th>ชื่อ/ธุรกิจ</th><th>เบอร์</th><th>วันที่</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($submissions as $s): ?>
      <tr>
        <td><?= $s['is_read'] ? '' : '<span class="badge badge-unread">ใหม่</span>' ?></td>
        <td><?= e($s['form_type']) ?></td>
        <td><?= e($s['name'] ?: $s['business_name']) ?></td>
        <td><?= e($s['phone']) ?></td>
        <td><?= e(date('d/m/Y H:i', strtotime($s['created_at']))) ?></td>
        <td><a href="submissions.php?id=<?= (int)$s['id'] ?>" class="btn btn-sm btn-secondary">ดู</a></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($submissions)): ?>
      <tr><td colspan="6" style="text-align:center;color:var(--admin-muted);">ยังไม่มีฟอร์ม</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php admin_footer(); ?>
