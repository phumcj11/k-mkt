<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$cases = [];
$dbError = false;
try {
    $cases = db()->query("SELECT * FROM case_studies WHERE status='published' ORDER BY sort_order ASC, id DESC")->fetchAll();
} catch (Throwable $e) {
    $dbError = true;
}
$activePage = 'case';

function metric_color(string $color): string {
    return match($color) {
        'blue' => 'var(--blue)',
        'green' => '#22c55e',
        default => 'var(--gold)',
    };
}
function metric_bg(string $color): string {
    return match($color) {
        'blue' => 'rgba(59,130,246,0.05)',
        'green' => 'rgba(34,197,94,0.05)',
        default => 'rgba(212,175,55,0.05)',
    };
}
function metric_border(string $color): string {
    return match($color) {
        'blue' => 'rgba(59,130,246,0.15)',
        'green' => 'rgba(34,197,94,0.15)',
        default => 'rgba(212,175,55,0.15)',
    };
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Case Study ผลงาน | กาญจน์ตลาด — ผลการตลาดจริง</title>
  <meta name="description" content="ผลงานและ Case Study จริงจาก กาญจน์ตลาด สำหรับธุรกิจกาญจนบุรี">
  <link rel="canonical" href="https://k-mkt.com/case-study.php">
  <meta property="og:title" content="Case Study ผลงาน | กาญจน์ตลาด">
  <meta property="og:url" content="https://k-mkt.com/case-study.php">
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
    <div class="section-tag reveal-up" style="justify-content:center;">✦ ผลงาน</div>
    <h1 class="headline-xl reveal-up" style="margin:16px 0 20px;">ผลงาน<span class="text-gold">ที่วัดได้จริง</span></h1>
    <p class="text-muted reveal-up" style="max-width:560px;line-height:1.8;margin:0 auto;">ทุก Case Study มาจากลูกค้าจริงของ กาญจน์ตลาด ตัวเลขจริง ไม่ปั้นแต่ง</p>
  </div>
</section>

<section class="section section-light">
  <div class="container">

    <?php if ($dbError): ?>
    <div style="background:#FEE2E2;border:1px solid #FECACA;border-radius:12px;padding:24px;text-align:center;margin-bottom:32px;">
      <p style="color:#991B1B;">ระบบ Database กำลังตั้งค่า — กรุณารอ deploy หรือติดต่อผู้ดูแล</p>
    </div>
    <?php endif; ?>

    <?php foreach ($cases as $c):
      $metrics = json_decode($c['metrics'] ?? '[]', true) ?: [];
    ?>
    <div class="case-feature reveal-up">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0;" class="md:grid-cols-2">
        <div class="case-content">
          <div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
            <span class="chip-gold" style="border-radius:999px;padding:4px 12px;font-size:0.78rem;"><?= e($c['industry_tag']) ?></span>
            <span class="chip" style="border-radius:999px;padding:4px 12px;font-size:0.78rem;"><?= e($c['duration']) ?></span>
          </div>
          <h2 style="font-size:1.6rem;font-weight:800;margin-bottom:16px;"><?= e($c['title']) ?><br><span class="text-gold"><?= e($c['subtitle']) ?></span></h2>
          <div style="margin-bottom:24px;">
            <div style="font-size:0.75rem;color:var(--gold);font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;">🔴 ปัญหา (Problem)</div>
            <p style="font-size:0.9rem;color:var(--text-muted);line-height:1.7;"><?= e($c['problem']) ?></p>
          </div>
          <div style="margin-bottom:24px;">
            <div style="font-size:0.75rem;color:var(--blue);font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;">🔵 กลยุทธ์ (Strategy)</div>
            <p style="font-size:0.9rem;color:var(--text-muted);line-height:1.7;"><?= e($c['strategy']) ?></p>
          </div>
          <div>
            <div style="font-size:0.75rem;color:#22c55e;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;">🟢 ผลลัพธ์ (Results)</div>
          </div>
        </div>
        <div style="padding:48px;">
          <?php if ($metrics): ?>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
            <?php foreach ($metrics as $m): ?>
            <div style="background:<?= metric_bg($m['color'] ?? 'gold') ?>;border:1px solid <?= metric_border($m['color'] ?? 'gold') ?>;border-radius:12px;padding:20px;text-align:center;">
              <div style="font-size:2rem;font-weight:900;color:<?= metric_color($m['color'] ?? 'gold') ?>;"><?= e($m['value']) ?></div>
              <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;"><?= e($m['label']) ?></div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <?php if ($c['quote']): ?>
          <div style="background:rgba(34,197,94,0.05);border:1px solid rgba(34,197,94,0.15);border-radius:12px;padding:20px;">
            <p style="font-size:0.88rem;color:var(--text);line-height:1.7;font-style:italic;">"<?= e($c['quote']) ?>"</p>
            <?php if ($c['quote_author']): ?>
            <div style="margin-top:12px;font-size:0.82rem;color:var(--gold);font-weight:600;">— <?= e($c['quote_author']) ?></div>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <?php if (empty($cases)): ?>
    <p class="text-muted text-center">ยังไม่มีผลงาน</p>
    <?php endif; ?>

    <div style="background:rgba(212,175,55,0.03);border:1px dashed rgba(212,175,55,0.2);border-radius:20px;padding:60px;text-align:center;" class="reveal-up">
      <div style="font-size:3rem;margin-bottom:16px;">📊</div>
      <h3 style="font-size:1.3rem;font-weight:700;margin-bottom:12px;">Case Study เพิ่มเติมกำลังมา</h3>
      <p class="text-muted" style="margin-bottom:28px;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.7;">เรากำลังรวบรวม Case Study จากลูกค้าเพิ่มเติม</p>
      <a href="contact.html" class="btn btn-primary magnetic">ปรึกษาฟรีวันนี้</a>
    </div>
  </div>
</section>

<section class="section section-navy-tint" style="background:linear-gradient(135deg, #EFF6FF, #FFFBF5);">
  <div class="container text-center">
    <div class="reveal-up">
      <h2 class="headline-lg" style="margin-bottom:20px;">ธุรกิจคุณจะเป็น <span class="text-gold">Case Study ต่อไป?</span></h2>
      <p class="text-muted" style="margin-bottom:36px;max-width:480px;margin-left:auto;margin-right:auto;">ปรึกษาทีมเราฟรี เราพร้อมวางแผนให้ผลลัพธ์แบบนี้กับธุรกิจของคุณ</p>
      <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
        <a href="contact.html" class="btn btn-primary magnetic">📞 ปรึกษาฟรี</a>
        <a href="free-audit.html" class="btn btn-outline magnetic">🔍 ตรวจสุขภาพการตลาด</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/public-footer.php'; ?>
</body>
</html>
