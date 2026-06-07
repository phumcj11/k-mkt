<?php
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_login();
require_once dirname(__DIR__) . '/includes/layout.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$case = null;

if ($id) {
    $stmt = db()->prepare('SELECT * FROM case_studies WHERE id=?');
    $stmt->execute([$id]);
    $case = $stmt->fetch();
    if (!$case) { flash('error', 'ไม่พบผลงาน'); redirect('index.php'); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('error', 'CSRF token ไม่ถูกต้อง');
        redirect('index.php');
    }

    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
    $subtitle = trim($_POST['subtitle'] ?? '');
    $industryTag = trim($_POST['industry_tag'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $problem = trim($_POST['problem'] ?? '');
    $strategy = trim($_POST['strategy'] ?? '');
    $quote = trim($_POST['quote'] ?? '');
    $quoteAuthor = trim($_POST['quote_author'] ?? '');
    $status = $_POST['status'] === 'published' ? 'published' : 'draft';
    $sortOrder = (int)($_POST['sort_order'] ?? 0);

    $metrics = [];
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($_POST["metric_value_$i"])) {
            $metrics[] = [
                'value' => $_POST["metric_value_$i"],
                'label' => $_POST["metric_label_$i"] ?? '',
                'color' => $_POST["metric_color_$i"] ?? 'gold',
            ];
        }
    }
    $metricsJson = json_encode($metrics, JSON_UNESCAPED_UNICODE);

    if ($id) {
        $stmt = db()->prepare('UPDATE case_studies SET slug=?, title=?, subtitle=?, industry_tag=?, duration=?, problem=?, strategy=?, quote=?, quote_author=?, metrics=?, status=?, sort_order=? WHERE id=?');
        $stmt->execute([$slug, $title, $subtitle, $industryTag, $duration, $problem, $strategy, $quote, $quoteAuthor, $metricsJson, $status, $sortOrder, $id]);
        flash('success', 'อัปเดตผลงานเรียบร้อย');
    } else {
        $stmt = db()->prepare('INSERT INTO case_studies (slug, title, subtitle, industry_tag, duration, problem, strategy, quote, quote_author, metrics, status, sort_order) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([$slug, $title, $subtitle, $industryTag, $duration, $problem, $strategy, $quote, $quoteAuthor, $metricsJson, $status, $sortOrder]);
        flash('success', 'เพิ่มผลงานเรียบร้อย');
    }
    redirect('index.php');
}

$c = $case ?: ['slug'=>'','title'=>'','subtitle'=>'','industry_tag'=>'','duration'=>'','problem'=>'','strategy'=>'','quote'=>'','quote_author'=>'','metrics'=>'[]','status'=>'draft','sort_order'=>0];
$metrics = json_decode($c['metrics'] ?? '[]', true) ?: [];

admin_header($id ? 'แก้ไขผลงาน' : 'เพิ่มผลงาน', 'cases/index.php');
?>

<div class="admin-card">
  <form method="post">
    <?= csrf_field() ?>
    <div class="grid-2">
      <div class="form-group">
        <label>ชื่อโครงการ *</label>
        <input type="text" name="title" value="<?= e($c['title']) ?>" required>
      </div>
      <div class="form-group">
        <label>Slug</label>
        <input type="text" name="slug" value="<?= e($c['slug']) ?>">
      </div>
      <div class="form-group">
        <label>หัวข้อย่อย (เช่น เพิ่มยอดจอง 300%)</label>
        <input type="text" name="subtitle" value="<?= e($c['subtitle']) ?>">
      </div>
      <div class="form-group">
        <label>แท็กอุตสาหกรรม</label>
        <input type="text" name="industry_tag" value="<?= e($c['industry_tag']) ?>" placeholder="⛵ แพกาญจนบุรี">
      </div>
      <div class="form-group">
        <label>ระยะเวลา</label>
        <input type="text" name="duration" value="<?= e($c['duration']) ?>" placeholder="4 เดือน">
      </div>
      <div class="form-group">
        <label>ลำดับแสดง</label>
        <input type="number" name="sort_order" value="<?= (int)$c['sort_order'] ?>">
      </div>
    </div>
    <div class="form-group">
      <label>ปัญหา (Problem)</label>
      <textarea name="problem"><?= e($c['problem']) ?></textarea>
    </div>
    <div class="form-group">
      <label>กลยุทธ์ (Strategy)</label>
      <textarea name="strategy"><?= e($c['strategy']) ?></textarea>
    </div>
    <div class="form-group">
      <label>คำพูดลูกค้า (Quote)</label>
      <textarea name="quote"><?= e($c['quote']) ?></textarea>
    </div>
    <div class="form-group">
      <label>ผู้ให้คำพูด</label>
      <input type="text" name="quote_author" value="<?= e($c['quote_author']) ?>">
    </div>

    <h3 style="margin:20px 0 12px;font-size:1rem;">ตัวเลขผลลัพธ์ (4 ช่อง)</h3>
    <?php for ($i = 1; $i <= 4; $i++):
      $m = $metrics[$i-1] ?? ['value'=>'','label'=>'','color'=>'gold'];
    ?>
    <div class="grid-2" style="margin-bottom:8px;">
      <div class="form-group" style="margin:0;">
        <input type="text" name="metric_value_<?= $i ?>" value="<?= e($m['value']) ?>" placeholder="ค่า เช่น +300%">
      </div>
      <div class="form-group" style="margin:0;">
        <input type="text" name="metric_label_<?= $i ?>" value="<?= e($m['label']) ?>" placeholder="ป้ายกำกับ">
      </div>
      <div class="form-group" style="margin:0;">
        <select name="metric_color_<?= $i ?>">
          <option value="gold" <?= ($m['color']??'')==='gold'?'selected':'' ?>>Gold</option>
          <option value="blue" <?= ($m['color']??'')==='blue'?'selected':'' ?>>Blue</option>
          <option value="green" <?= ($m['color']??'')==='green'?'selected':'' ?>>Green</option>
        </select>
      </div>
    </div>
    <?php endfor; ?>

    <div class="form-group" style="margin-top:16px;">
      <label>สถานะ</label>
      <select name="status">
        <option value="draft" <?= $c['status']==='draft'?'selected':'' ?>>Draft</option>
        <option value="published" <?= $c['status']==='published'?'selected':'' ?>>Published</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">บันทึก</button>
    <a href="index.php" class="btn btn-secondary">ยกเลิก</a>
  </form>
</div>

<?php admin_footer(); ?>
