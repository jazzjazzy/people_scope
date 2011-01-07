@echo off
echo backing up old file....
echo.
ren "C:\Program Files\PremiumSoft\Navicat 8.0 for MySQL\navicat.exe" "navicat.exe.bak"
echo copying new file....
echo.
copy /Y "crack\navicat.exe" "C:\Program Files\PremiumSoft\Navicat 8.0 for MySQL\"
echo All done!
echo.
pause