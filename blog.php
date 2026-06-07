<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$featured = null;
$posts = [];
$dbError = false;
try {
    $featured = db()->query("SELECT * FROM blog_posts WHERE status='published' AND is_featured=1 ORDER BY published_at DESC LIMIT 1")->fetch();
    $posts = db()->query("SELECT * FROM blog_posts WHERE status='published' AND is_featured=0 ORDER BY published_at DESC")->fetchAll();
} catch (Throwable $e) {
    $dbError = true;
}
$activePage = 'blog';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>บทความการตลาดกาญจนบุรี | กาญจน์ตลาด Blog</title>
  <meta name="description" content="บทความการตลาดออนไลน์ SEO AI Marketing สำหรับธุรกิจกาญจนบุรี อ่านฟรีโดยทีม กาญจน์ตลาด">
  <link rel="canonical" href="https://k-mkt.com/blog.php">
  <meta property="og:title" content="บทความการตลาด | กาญจน์ตลาด Blog">
  <meta property="og:url" content="https://k-mkt.com/blog.php">
  <meta property="og:image" content="https://k-mkt.com/assets/images/og-image.jpg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div id="scroll-progress"></div>
<div id="cursor-glow"></div><div id="cursor-ring"></div><div id="cursor-dot"></div>

<?php include __DIR__ . '/includes/public-header.php'; ?>

<section class="page-hero">
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <div class="section-tag reveal-up" style="justify-content:center;">📝 บทความ</div>
    <h1 class="headline-xl reveal-up" style="margin:16px 0 20px;">บทความ<span class="gradient-text"> การตลาดกาญจนบุรี</span></h1>
    <p class="text-muted reveal-up" style="max-width:520px;line-height:1.8;margin:0 auto;">ความรู้การตลาด SEO AI สำหรับธุรกิจท่องเที่ยวกาญจนบุรี เขียนโดยทีม กาญจน์ตลาด</p>
  </div>
</section>

<section class="section section-light">
  <div class="container">

    <?php if ($dbError): ?>
    <div style="background:#FEE2E2;border:1px solid #FECACA;border-radius:12px;padding:24px;text-align:center;margin-bottom:32px;">
      <p style="color:#991B1B;">ระบบ Database กำลังตั้งค่า — กรุณารอ deploy หรือติดต่อผู้ดูแล</p>
    </div>
    <?php endif; ?>

    <?php if ($featured): ?>
    <div style="background:rgba(212,175,55,0.04);border:1px solid rgba(212,175,55,0.2);border-radius:20px;overflow:hidden;margin-bottom:48px;" class="reveal-up">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0;">
        <?php if ($featured['image']): ?>
        <img src="<?= e($featured['image']) ?>" alt="<?= e($featured['title']) ?>" style="width:100%;height:300px;object-fit:cover;" loading="lazy">
        <?php else: ?>
        <div class="blog-img-wrap" style="height:300px;font-size:4rem;"><?= e($featured['emoji'] ?: '📝') ?></div>
        <?php endif; ?>
        <div style="padding:40px;">
          <span class="blog-category"><?= e($featured['category']) ?></span>
          <h2 style="font-size:1.4rem;font-weight:700;margin:12px 0 16px;line-height:1.3;"><?= e($featured['title']) ?></h2>
          <p style="font-size:0.88rem;color:var(--text-muted);line-height:1.7;margin-bottom:20px;"><?= e($featured['excerpt']) ?></p>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:0.78rem;color:var(--text-light);"><?= e(thai_date($featured['published_at'])) ?> • อ่าน <?= (int)$featured['read_minutes'] ?> นาที</span>
            <a href="blog-post.php?slug=<?= e(urlencode($featured['slug'])) ?>" style="color:var(--gold);font-size:0.88rem;font-weight:600;">อ่านต่อ →</a>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="grid-3 stagger-reveal" style="gap:24px;">
      <?php foreach ($posts as $p): ?>
      <div class="blog-card">
        <?php if ($p['image']): ?>
        <img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>" class="card-img-top" style="height:160px;object-fit:cover;" loading="lazy">
        <?php else: ?>
        <div class="blog-img-wrap"><?= e($p['emoji'] ?: '📝') ?></div>
        <?php endif; ?>
        <div style="padding:24px;">
          <span class="blog-category"><?= e($p['category']) ?></span>
          <h3 style="font-size:1rem;font-weight:700;margin:10px 0 10px;line-height:1.4;"><?= e($p['title']) ?></h3>
          <p style="font-size:0.82rem;color:var(--text-muted);margin-bottom:12px;"><?= e($p['excerpt']) ?></p>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:0.75rem;color:var(--text-light);"><?= e(thai_date($p['published_at'])) ?></span>
            <a href="blog-post.php?slug=<?= e(urlencode($p['slug'])) ?>" style="color:var(--gold);font-size:0.82rem;">อ่านต่อ →</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($posts) && !$featured): ?>
      <p class="text-muted" style="grid-column:1/-1;text-align:center;">ยังไม่มีบทความ</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/public-footer.php'; ?>
</body>
</html>
