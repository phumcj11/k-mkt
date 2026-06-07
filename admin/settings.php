<?php
require_once dirname(__DIR__) . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/layout.php';

$keys = ['phone', 'phone_tel', 'line_id', 'line_url', 'email', 'facebook_url', 'messenger_url', 'address'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('error', 'CSRF token ไม่ถูกต้อง');
        redirect('settings.php');
    }
    foreach ($keys as $key) {
        set_setting($key, trim($_POST[$key] ?? ''));
    }
    flash('success', 'บันทึกการตั้งค่าเรียบร้อย');
    redirect('settings.php');
}

$settings = get_settings();
admin_header('ตั้งค่าข้อมูลติดต่อ', 'settings.php');
?>

<div class="admin-card">
  <form method="post">
    <?= csrf_field() ?>
    <div class="grid-2">
      <div class="form-group">
        <label>เบอร์โทร (แสดงผล)</label>
        <input type="text" name="phone" value="<?= e($settings['phone'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>เบอร์โทร (tel: link)</label>
        <input type="text" name="phone_tel" value="<?= e($settings['phone_tel'] ?? '') ?>" placeholder="+66819007895">
      </div>
      <div class="form-group">
        <label>LINE ID</label>
        <input type="text" name="line_id" value="<?= e($settings['line_id'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>LINE URL</label>
        <input type="url" name="line_url" value="<?= e($settings['line_url'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>อีเมล</label>
        <input type="email" name="email" value="<?= e($settings['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>ที่อยู่</label>
        <input type="text" name="address" value="<?= e($settings['address'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Facebook URL</label>
        <input type="url" name="facebook_url" value="<?= e($settings['facebook_url'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Messenger URL</label>
        <input type="url" name="messenger_url" value="<?= e($settings['messenger_url'] ?? '') ?>">
      </div>
    </div>
    <button type="submit" class="btn btn-primary">บันทึก</button>
  </form>
</div>

<?php admin_footer(); ?>
