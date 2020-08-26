@echo off

set rootDir="%~dp0/../.."
set phpunit="vendor/phpunit/phpunit/phpunit"

cd %rootDir%
php %phpunit%
pause