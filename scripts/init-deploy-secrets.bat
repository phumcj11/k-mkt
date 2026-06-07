@echo off
chcp 65001 > nul
echo.
echo ═══════════════════════════════════════
echo   ตั้งค่า deploy.secrets (ครั้งเดียว)
echo ═══════════════════════════════════════
echo.
echo ใส่รหัสผ่าน MySQL ของ database pcj_kmkt บน VPS
echo (จาก DirectAdmin ^> MySQL Management ^> คลิก database pcj_kmkt)
echo.
echo หมายเหตุ: ใช้รหัสของ USER pcj_kmkt ไม่ใช่รหัส root ของ server
echo.
set /p DB_PASS="รหัสผ่าน MySQL: "
if "%DB_PASS%"=="" (
  echo ยกเลิก — ไม่ได้ใส่รหัสผ่าน
  pause
  exit /b 1
)
(
echo # สร้างโดย init-deploy-secrets.bat — อย่า commit
echo DB_PASS=%DB_PASS%
) > deploy.secrets
echo.
echo ✅ บันทึก deploy.secrets แล้ว
echo    ต่อไปกด deploy.bat ได้เลย
echo.
pause
