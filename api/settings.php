<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/helpers.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=300');

try {
    $settings = get_settings();
    json_response([
        'success' => true,
        'data' => [
            'phone' => $settings['phone'] ?? '081-900-7895',
            'phone_tel' => $settings['phone_tel'] ?? '+66819007895',
            'line_id' => $settings['line_id'] ?? '@k-mkt',
            'line_url' => $settings['line_url'] ?? 'https://line.me/kmkt',
            'email' => $settings['email'] ?? 'hello@k-mkt.com',
            'facebook_url' => $settings['facebook_url'] ?? '',
            'messenger_url' => $settings['messenger_url'] ?? '',
            'address' => $settings['address'] ?? 'กาญจนบุรี ประเทศไทย',
        ],
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'data' => [
            'phone' => '081-900-7895',
            'phone_tel' => '+66819007895',
            'line_id' => '@k-mkt',
            'line_url' => 'https://line.me/kmkt',
            'email' => 'hello@k-mkt.com',
        ],
    ]);
}
