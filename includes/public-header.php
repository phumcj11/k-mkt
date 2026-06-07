<?php
/** Shared nav snippet — pass $activePage to highlight nav */
$activePage = $activePage ?? '';
?>
<header id="main-header">
  <div class="container">
    <nav class="flex items-center justify-between">
      <a href="index.html" class="nav-logo">กาญจน์ตลาด</a>
      <div class="nav-desktop hidden md:flex items-center gap-6">
        <a href="services.html" class="nav-link">บริการ</a>
        <a href="ai-marketing.html" class="nav-link">AI Marketing</a>
        <a href="seo-kanchanaburi.html" class="nav-link">SEO</a>
        <a href="google-map-marketing.html" class="nav-link">Google Map</a>
        <a href="case-study.php" class="nav-link"<?= $activePage==='case'?' style="color:var(--gold);"':'' ?>>ผลงาน</a>
        <a href="blog.php" class="nav-link"<?= $activePage==='blog'?' style="color:var(--gold);"':'' ?>>บทความ</a>
        <a href="contact.html" class="nav-link">ติดต่อ</a>
        <a href="free-audit.html" class="btn btn-primary magnetic" style="padding:10px 20px;font-size:0.85rem;">ตรวจสุขภาพฟรี</a>
      </div>
      <button id="hamburger" class="hamburger md:hidden"><span></span><span></span><span></span></button>
    </nav>
  </div>
</header>

<div id="mobile-menu">
  <button id="mobile-close" style="position:absolute;top:24px;right:24px;font-size:2rem;color:white;background:none;border:none;cursor:pointer;">✕</button>
  <a href="index.html" class="mobile-nav-link">หน้าแรก</a>
  <a href="services.html" class="mobile-nav-link">บริการ</a>
  <a href="blog.php" class="mobile-nav-link">บทความ</a>
  <a href="case-study.php" class="mobile-nav-link">ผลงาน</a>
  <a href="contact.html" class="mobile-nav-link">ติดต่อ</a>
  <a href="free-audit.html" class="btn btn-primary" style="margin-top:16px;">ตรวจสุขภาพฟรี</a>
</div>
