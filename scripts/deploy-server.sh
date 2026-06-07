#!/bin/bash
# Post-deploy script บน VPS — รันหลัง git pull
set -e

WEB_ROOT="/home/pcj/domains/k-mkt.com/public_html"
PRIVATE_DIR="/home/pcj/domains/k-mkt.com/private"
DB_USER="pcj_kmkt"
DB_NAME="pcj_kmkt"
cd "$WEB_ROOT"

echo "==> git pull"
git pull origin main

echo "==> โหลด DB credentials"
read_db_pass() {
  if [ -n "$DB_PASS_ENV" ]; then
    printf '%s' "$DB_PASS_ENV" | tr -d '\r'
    return
  fi
  local env_file="$PRIVATE_DIR/db.env"
  if [ -f "$env_file" ]; then
    # ลบ CRLF จาก Windows + ไม่ใช้ source (รองรับรหัสผ่านที่มีอักขระพิเศษ)
    sed -i 's/\r$//' "$env_file" 2>/dev/null || true
    grep -m1 '^DB_PASS=' "$env_file" | cut -d= -f2- | tr -d '\r\n'
    return
  fi
  echo ""
}

DB_PASS="$(read_db_pass)"

if [ -z "$DB_PASS" ]; then
  echo "ERROR: ไม่พบรหัสผ่าน DB"
  echo "       สร้าง deploy.secrets บนเครื่อง local หรือ $PRIVATE_DIR/db.env บน server"
  exit 1
fi

echo "==> สร้าง/อัปเดต config.local.php"
K_MKT_DB_PASS="$DB_PASS" php -r '
$pass = getenv("K_MKT_DB_PASS");
$config = [
    "db_host" => "localhost",
    "db_name" => "'"$DB_NAME"'",
    "db_user" => "'"$DB_USER"'",
    "db_pass" => $pass,
    "db_charset" => "utf8mb4",
    "site_url" => "https://k-mkt.com",
    "upload_dir" => "'"$WEB_ROOT"'/uploads",
    "upload_url" => "/uploads",
];
file_put_contents("config.local.php", "<?php\nreturn " . var_export($config, true) . ";\n");
'
chmod 644 config.local.php

echo "==> ปรับ .htaccess สำหรับ production"
if [ -f .htaccess ]; then
  sed -i 's|RewriteBase /k-mkt/|RewriteBase /|g' .htaccess
fi

MYSQL="mysql"
command -v mysql >/dev/null 2>&1 || MYSQL="/usr/local/mysql/bin/mysql"

mysql_test() {
  local err
  err=$("$@" -e "SELECT 1" 2>&1) && return 0
  echo "$err"
  return 1
}

MYSQL_ARGS=()
err=""
echo "==> ทดสอบเชื่อมต่อ Database $DB_NAME"
if err=$(mysql_test "$MYSQL" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME"); then
  MYSQL_ARGS=("$MYSQL" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME")
elif err=$(mysql_test "$MYSQL" -u "$DB_USER" -p"$DB_PASS" -h 127.0.0.1 "$DB_NAME"); then
  MYSQL_ARGS=("$MYSQL" -u "$DB_USER" -p"$DB_PASS" -h 127.0.0.1 "$DB_NAME")
else
  for sock in /var/lib/mysql/mysql.sock /tmp/mysql.sock; do
    if [ -S "$sock" ] && err=$(mysql_test "$MYSQL" -u "$DB_USER" -p"$DB_PASS" --socket="$sock" "$DB_NAME"); then
      MYSQL_ARGS=("$MYSQL" -u "$DB_USER" -p"$DB_PASS" --socket="$sock" "$DB_NAME")
      break
    fi
  done
fi

if [ ${#MYSQL_ARGS[@]} -eq 0 ]; then
  echo "ERROR: เชื่อมต่อ MySQL ไม่ได้"
  echo "       $(echo "$err" | head -1)"
  echo "       ตรวจ: DirectAdmin > MySQL Management > user $DB_USER"
  echo "       รันใหม่: scripts\\init-deploy-secrets.bat แล้ว deploy.bat"
  echo "       (อ่านรหัสผ่านได้ ${#DB_PASS} ตัวอักษร — ถ้าเกินจริงอาจมี CRLF)"
  exit 1
fi

echo "==> Database migrate (schema)"
"${MYSQL_ARGS[@]}" < database/schema.sql

echo "==> Database seed (ถ้ายังไม่มีข้อมูล)"
USER_COUNT=$("${MYSQL_ARGS[@]}" -N -e "SELECT COUNT(*) FROM users" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
  "${MYSQL_ARGS[@]}" < database/seed.sql
  echo "    Seed สำเร็จ (admin / admin123)"
else
  echo "    ข้าม seed — มีข้อมูลอยู่แล้ว"
fi

echo "==> Permissions"
mkdir -p uploads/blog uploads/cases
chmod 755 uploads uploads/blog uploads/cases
chmod 644 uploads/.htaccess 2>/dev/null || true
chmod +x scripts/deploy-server.sh 2>/dev/null || true

echo "==> ตรวจสอบ PHP + health"
php -r "require 'includes/db.php'; echo 'DB OK: '.DB_NAME.PHP_EOL;"
curl -sf "https://k-mkt.com/health.php" 2>/dev/null || true

echo ""
echo "✅ Deploy สำเร็จ! https://k-mkt.com"
echo "   Admin: https://k-mkt.com/admin/"
