@echo off
chcp 65001 > nul
echo.
echo ╔══════════════════════════════════╗
echo ║   กาญจน์ตลาด — Push to GitHub   ║
echo ╚══════════════════════════════════╝
echo.

set /p MSG="📝 ข้อความ Commit (กด Enter ใช้ 'update'): "
if "%MSG%"=="" set MSG=update

echo.
echo 📦 กำลัง Push: %MSG%
echo.

git add .
git commit -m "%MSG%"
git push origin main

echo.
echo ✅ Push สำเร็จ! เว็บ GitHub อัปเดตแล้ว
echo.
pause
