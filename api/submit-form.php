<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/helpers.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$formType = trim($input['form_type'] ?? '');
$allowedTypes = ['contact', 'audit', 'audit-full'];

if (!in_array($formType, $allowedTypes, true)) {
    json_response(['success' => false, 'message' => 'ประเภทฟอร์มไม่ถูกต้อง'], 400);
}

$name = trim($input['name'] ?? '');
$phone = trim($input['phone'] ?? '');
$email = trim($input['email'] ?? '');
$businessName = trim($input['business_name'] ?? '');
$businessType = trim($input['business_type'] ?? '');
$serviceInterest = trim($input['service_interest'] ?? '');
$message = trim($input['message'] ?? '');

if ($formType === 'contact' && (!$name || !$phone)) {
    json_response(['success' => false, 'message' => 'กรุณากรอกชื่อและเบอร์โทร'], 400);
}

if (in_array($formType, ['audit', 'audit-full'], true) && (!$businessName || !$phone)) {
    json_response(['success' => false, 'message' => 'กรุณากรอกชื่อธุรกิจและเบอร์โทร'], 400);
}

$extraKeys = ['website', 'facebook', 'line_oa', 'google_map'];
$extra = [];
foreach ($extraKeys as $key) {
    if (!empty($input[$key])) {
        $extra[$key] = trim($input[$key]);
    }
}

try {
    $stmt = db()->prepare('INSERT INTO form_submissions (form_type, name, phone, email, business_name, business_type, service_interest, message, extra_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $formType,
        $name,
        $phone,
        $email,
        $businessName,
        $businessType,
        $serviceInterest,
        $message,
        $extra ? json_encode($extra, JSON_UNESCAPED_UNICODE) : null,
    ]);

    json_response(['success' => true, 'message' => 'ส่งข้อมูลสำเร็จ ทีมงานจะติดต่อกลับภายใน 24 ชั่วโมง']);
} catch (Exception $e) {
    json_response(['success' => false, 'message' => 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง'], 500);
}
