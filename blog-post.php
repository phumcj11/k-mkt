<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$slug = trim($_GET['slug'] ?? '');
if (!$slug) { header('Location: blog.php'); exit; }

$stmt = db()->prepare("SELECT * FROM blog_posts WHERE slug=? AND status='published' LIMIT 1");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    die('ไม่พบบทความ');
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($post['title']) ?> | กาญจน์ตลาด Blog</title>
  <meta name="description" content="<?= e($post['excerpt']) ?>">
  <link rel="canonical" href="https://k-mkt.com/blog-post.php?slug=<?= e(urlencode($post['slug'])) ?>">
  <meta property="og:title" content="<?= e($post['title']) ?>">
  <meta property="og:description" content="<?= e($post['excerpt']) ?>">
  <meta property="og:url" content="https://k-mkt.com/blog-post.php?slug=<?= e(urlencode($post['slug'])) ?>">
  <?php if ($post['image']): ?><meta property="og:image" content="https://k-mkt.com/<?= e(ltrim($post['image'], '/')) ?>"><?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div id="scroll-progress"></div>
<div id="cursor-glow"></div><div id="cursor-ring"></div><div id="cursor-dot"></div>

<?php $activePage = 'blog'; include __DIR__ . '/includes/public-header.php'; ?>

<section class="page-hero">
  <div class="container" style="position:relative;z-index:1;max-width:800px;">
    <nav class="breadcrumb" style="margin-bottom:24px;">
      <a href="index.html">หน้าแรก</a><span style="opacity:0.4;">/</span>
      <a href="blog.php">บทความ</a><span style="opacity:0.4;">/</span>
      <span><?= e($post['category']) ?></span>
    </nav>
    <span class="blog-category"><?= e($post['category']) ?></span>
    <h1 class="headline-xl reveal-up" style="margin:16px 0 20px;"><?= e($post['title']) ?></h1>
    <p class="text-muted" style="font-size:0.9rem;"><?= e(thai_date($post['published_at'])) ?> • อ่าน <?= (int)$post['read_minutes'] ?> นาที</p>
  </div>
</section>

<section class="section section-light">
  <div class="container" style="max-width:760px;">
    <?php if ($post['image']): ?>
    <img src="<?= e($post['image']) ?>" alt="<?= e($post['title']) ?>" style="width:100%;border-radius:16px;margin-bottom:32px;max-height:400px;object-fit:cover;" loading="lazy">
    <?php endif; ?>
    <div class="reveal-up" style="font-size:1.05rem;line-height:1.9;color:var(--text);">
      <?= $post['content'] ?>
    </div>
    <div style="margin-top:48px;padding-top:32px;border-top:1px solid var(--border);text-align:center;">
      <a href="blog.php" class="btn btn-outline magnetic">← กลับหน้าบทความ</a>
      <a href="contact.html" class="btn btn-primary magnetic" style="margin-left:12px;">ปรึกษาการตลาดฟรี</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/public-footer.php'; ?>
</body>
</html>
