#!/bin/bash
# Post-deploy script บน VPS — รันหลัง git pull
set -e

WEB_ROOT="/home/pcj/domains/k-mkt.com/public_html"
PRIVATE_DIR="/home/pcj/domains/k-mkt.com/private"
cd "$WEB_ROOT"

echo "==> git pull"
git pull origin main

echo "==> โหลด DB credentials"
DB_PASS=""
if [ -n "$DB_PASS_ENV" ]; then
  DB_PASS="$DB_PASS_ENV"
elif [ -f "$PRIVATE_DIR/db.env" ]; then
  # shellcheck source=/dev/null
  source "$PRIVATE_DIR/db.env"
  DB_PASS="${DB_PASS:-}"
fi

if [ -z "$DB_PASS" ]; then
  echo "ERROR: ไม่พบรหัสผ่าน DB"
  echo "       สร้าง deploy.secrets บนเครื่อง local หรือ $PRIVATE_DIR/db.env บน server"
  exit 1
fi

echo "==> สร้าง/อัปเดต config.local.php"
cat > config.local.php << EOF
<?php
return [
    'db_host' => 'localhost',
    'db_name' => 'pcj_kmkt',
    'db_user' => 'pcj_kmkt',
    'db_pass' => '${DB_PASS}',
    'db_charset' => 'utf8mb4',
    'site_url' => 'https://k-mkt.com',
    'upload_dir' => '${WEB_ROOT}/uploads',
    'upload_url' => '/uploads',
];
EOF
chmod 644 config.local.php

echo "==> ปรับ .htaccess สำหรับ production"
if [ -f .htaccess ]; then
  sed -i 's|RewriteBase /k-mkt/|RewriteBase /|g' .htaccess
fi

echo "==> Database migrate (schema)"
mysql -u pcj_kmkt -p"${DB_PASS}" pcj_kmkt < database/schema.sql

echo "==> Database seed (ถ้ายังไม่มีข้อมูล)"
USER_COUNT=$(mysql -u pcj_kmkt -p"${DB_PASS}" pcj_kmkt -N -e "SELECT COUNT(*) FROM users" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
  mysql -u pcj_kmkt -p"${DB_PASS}" pcj_kmkt < database/seed.sql
  echo "    Seed สำเร็จ (admin / admin123)"
else
  echo "    ข้าม seed — มีข้อมูลอยู่แล้ว"
fi

echo "==> Permissions"
mkdir -p uploads/blog uploads/cases
chmod 755 uploads uploads/blog uploads/cases
chmod 644 uploads/.htaccess 2>/dev/null || true
chmod +x scripts/deploy-server.sh 2>/dev/null || true

echo "==> ตรวจสอบ PHP"
php -r "require 'includes/db.php'; echo 'DB OK: '.DB_NAME.PHP_EOL;"

echo ""
echo "✅ Deploy สำเร็จ! https://k-mkt.com"
echo "   Admin: https://k-mkt.com/admin/"
