<?php
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_login();
require_once dirname(__DIR__) . '/includes/layout.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = null;

if ($id) {
    $stmt = db()->prepare('SELECT * FROM blog_posts WHERE id=?');
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    if (!$post) { flash('error', 'ไม่พบบทความ'); redirect('index.php'); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('error', 'CSRF token ไม่ถูกต้อง');
        redirect('index.php');
    }

    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $emoji = trim($_POST['emoji'] ?? '');
    $readMinutes = (int)($_POST['read_minutes'] ?? 5);
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $status = $_POST['status'] === 'published' ? 'published' : 'draft';
    $publishedAt = $_POST['published_at'] ?: date('Y-m-d');
    $image = $post['image'] ?? '';

    if (!empty($_FILES['image']['name'])) {
        $uploaded = upload_image($_FILES['image'], 'blog');
        if ($uploaded) $image = $uploaded;
    }

    if ($id) {
        $stmt = db()->prepare('UPDATE blog_posts SET slug=?, title=?, excerpt=?, content=?, category=?, image=?, emoji=?, read_minutes=?, is_featured=?, status=?, published_at=? WHERE id=?');
        $stmt->execute([$slug, $title, $excerpt, $content, $category, $image, $emoji, $readMinutes, $isFeatured, $status, $publishedAt, $id]);
        flash('success', 'อัปเดตบทความเรียบร้อย');
    } else {
        $stmt = db()->prepare('INSERT INTO blog_posts (slug, title, excerpt, content, category, image, emoji, read_minutes, is_featured, status, published_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([$slug, $title, $excerpt, $content, $category, $image, $emoji, $readMinutes, $isFeatured, $status, $publishedAt]);
        flash('success', 'เพิ่มบทความเรียบร้อย');
    }
    redirect('index.php');
}

$p = $post ?: ['slug'=>'','title'=>'','excerpt'=>'','content'=>'','category'=>'','image'=>'','emoji'=>'','read_minutes'=>5,'is_featured'=>0,'status'=>'draft','published_at'=>date('Y-m-d')];

admin_header($id ? 'แก้ไขบทความ' : 'เพิ่มบทความ', 'blog/index.php');
?>

<div class="admin-card">
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="form-group">
      <label>หัวข้อ *</label>
      <input type="text" name="title" value="<?= e($p['title']) ?>" required>
    </div>
    <div class="grid-2">
      <div class="form-group">
        <label>Slug (URL)</label>
        <input type="text" name="slug" value="<?= e($p['slug']) ?>" placeholder="auto-generate">
      </div>
      <div class="form-group">
        <label>หมวดหมู่</label>
        <input type="text" name="category" value="<?= e($p['category']) ?>">
      </div>
    </div>
    <div class="form-group">
      <label>คำโปรย (Excerpt)</label>
      <textarea name="excerpt"><?= e($p['excerpt']) ?></textarea>
    </div>
    <div class="form-group">
      <label>เนื้อหา (HTML ได้)</label>
      <textarea name="content" style="min-height:240px;"><?= e($p['content']) ?></textarea>
    </div>
    <div class="grid-2">
      <div class="form-group">
        <label>Emoji (ถ้าไม่มีรูป)</label>
        <input type="text" name="emoji" value="<?= e($p['emoji']) ?>" placeholder="📝">
      </div>
      <div class="form-group">
        <label>เวลาอ่าน (นาที)</label>
        <input type="number" name="read_minutes" value="<?= (int)$p['read_minutes'] ?>" min="1">
      </div>
      <div class="form-group">
        <label>วันที่เผยแพร่</label>
        <input type="date" name="published_at" value="<?= e($p['published_at'] ?? date('Y-m-d')) ?>">
      </div>
      <div class="form-group">
        <label>สถานะ</label>
        <select name="status">
          <option value="draft" <?= $p['status']==='draft'?'selected':'' ?>>Draft</option>
          <option value="published" <?= $p['status']==='published'?'selected':'' ?>>Published</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label>รูปภาพ<?= $p['image'] ? ' (ปัจจุบัน: '.e($p['image']).')' : '' ?></label>
      <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
    </div>
    <div class="form-group">
      <label><input type="checkbox" name="is_featured" value="1" <?= $p['is_featured']?'checked':'' ?>> บทความเด่น (Featured)</label>
    </div>
    <button type="submit" class="btn btn-primary">บันทึก</button>
    <a href="index.php" class="btn btn-secondary">ยกเลิก</a>
  </form>
</div>

<?php admin_footer(); ?>
