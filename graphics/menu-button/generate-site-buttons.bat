@echo off
:: ============================================================
::  generate-site-buttons.bat  -  Generate all menu button PNGs
::                                for the Eva Bublova website
::
::  Usage:
::    generate-site-buttons.bat [--color-active #rrggbb]
::                              [--color-inactive #rrggbb]
::                              [--size PX]
::                              [--out DIR]
::                              [--scm-only FILE]
::
::  Defaults:
::    --color-active   #f76900
::    --color-inactive #301c28
::    --size           14
::    --out            <script dir>\out\site-buttons
::
::  Output:
::    <DIR>\active\   *.png
::    <DIR>\inactive\ *.png
::
::  Requirements:
::    - Python 3 in PATH
::    - GIMP 2.8 at D:\app-portable\gimp\App\gimp\bin\gimp-2.8.exe
::
::  Examples:
::    generate-site-buttons.bat
::    generate-site-buttons.bat --out C:\staging\buttons
::    generate-site-buttons.bat --color-active #cc5500 --out C:\staging\buttons
:: ============================================================

setlocal EnableDelayedExpansion

set "PYFILE=%~dp0generate-site-buttons.py"
if not exist "!PYFILE!" (
    echo ERROR: generate-site-buttons.py not found next to this script.
    exit /b 1
)

python "!PYFILE!" %*

exit /b %errorlevel%
