@echo off
chcp 65001 > nul
setlocal EnableDelayedExpansion

echo.
echo ╔══════════════════════════════════════╗
echo ║   กาญจน์ตลาด — Push + Deploy VPS    ║
echo ╚══════════════════════════════════════╝
echo.

REM ── ตรวจ deploy.secrets ──
if not exist "deploy.secrets" (
  echo ⚠️  ยังไม่มี deploy.secrets
  echo    รันครั้งแรก: scripts\init-deploy-secrets.bat
  echo    เพื่อใส่รหัสผ่าน MySQL ของ pcj_kmkt
  echo.
  set /p RUN_INIT="ต้องการตั้งค่าตอนนี้เลยไหม? (Y/N): "
  if /i "!RUN_INIT!"=="Y" (
    call scripts\init-deploy-secrets.bat
    if not exist "deploy.secrets" exit /b 1
  ) else (
    echo ยกเลิก deploy
    pause
    exit /b 1
  )
)

REM ── อ่าน DB_PASS จาก deploy.secrets ──
set DB_PASS=
for /f "usebackq tokens=1,* delims==" %%a in ("deploy.secrets") do (
  set "KEY=%%a"
  set "VAL=%%b"
  if "!KEY!"=="DB_PASS" set "DB_PASS=!VAL!"
)

if "!DB_PASS!"=="" (
  echo ❌ ไม่พบ DB_PASS ใน deploy.secrets
  pause
  exit /b 1
)

set /p MSG="📝 ข้อความ Commit (กด Enter ใช้ 'update'): "
if "%MSG%"=="" set MSG=update

echo.
echo 📦 Step 1: Push to GitHub...
echo.
git add .
git commit -m "%MSG%" 2>nul || echo    (ไม่มีไฟล์เปลี่ยน — ข้าม commit)
git push origin main
if errorlevel 1 (
  echo ❌ Push ล้มเหลว
  pause
  exit /b 1
)

echo.
echo 🚀 Step 2: Deploy to VPS (pcj_kmkt)...
echo.

REM ส่ง credentials ไป server (นอก public_html)
ssh root@119.59.102.235 "mkdir -p /home/pcj/domains/k-mkt.com/private && chmod 700 /home/pcj/domains/k-mkt.com/private"
scp deploy.secrets root@119.59.102.235:/home/pcj/domains/k-mkt.com/private/db.env

REM git pull ก่อน แล้วรัน deploy script
ssh root@119.59.102.235 "cd /home/pcj/domains/k-mkt.com/public_html && git pull origin main && bash scripts/deploy-server.sh"

if errorlevel 1 (
  echo.
  echo ❌ Deploy ล้มเหลว — ตรวจ SSH / รหัสผ่าน DB / database pcj_kmkt
  pause
  exit /b 1
)

echo.
echo ✅ เสร็จสิ้น!
echo    เว็บ:  https://k-mkt.com
echo    Admin: https://k-mkt.com/admin/
echo.
pause
