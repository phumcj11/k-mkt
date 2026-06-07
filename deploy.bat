@echo off
chcp 65001 > nul
echo.
echo ╔══════════════════════════════════════╗
echo ║     K-MKT — Push + Deploy VPS       ║
echo ╚══════════════════════════════════════╝
echo.

set /p MSG="📝 ข้อความ Commit (กด Enter ใช้ 'update'): "
if "%MSG%"=="" set MSG=update

echo.
echo 📦 Step 1: Push to GitHub...
echo.
git add .
git commit -m "%MSG%"
git push origin main

echo.
echo 🚀 Step 2: Deploy to VPS...
echo.
ssh root@119.59.102.235 "cd /home/pcj/domains/k-mkt.com/public_html && git pull origin main && echo ✅ Deploy สำเร็จ!"

echo.
echo ✅ เสร็จสิ้น! เว็บ k-mkt.com อัปเดตแล้ว
echo.
pause
