<?php
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    db()->prepare('DELETE FROM case_studies WHERE id=?')->execute([$id]);
    flash('success', 'ลบผลงานเรียบร้อย');
}
redirect('index.php');
