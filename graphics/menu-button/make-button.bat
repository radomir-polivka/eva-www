@echo off
:: ============================================================
::  make-button.bat  -  Generate a one- or two-line menu button PNG
::
::  Usage:
::    make-button.bat LINE1 [--line2 LINE2] [--color #rrggbb]
::                    [--size PX] [--out DIR] [--filename NAME]
::                    [--scm-only FILE]
::
::  Arguments:
::    LINE1              First (or only) line of button text  (required)
::    --line2 LINE2      Second line (omit for single-line button)
::    --color #rrggbb    Text color                           (default: #cc5500)
::    --size  PX         Font size in pixels                  (default: 14)
::    --out   DIR        Output directory                     (default: .\out)
::    --filename NAME    Output filename without extension    (default: button)
::    --scm-only FILE    Dump Script-Fu to FILE, don't run GIMP
::
::  Output:
::    <DIR>\<NAME>.png
::
::  Requirements:
::    - Python 3 in PATH
::    - GIMP 2.8 at D:\app-portable\gimp\App\gimp\bin\gimp-2.8.exe
::
::  Examples:
::    make-button.bat "Životopis"
::    make-button.bat "Životopis" --color #cc5500 --out C:\out --filename zivotopis
::    make-button.bat "For concert" --line2 "organizers" --filename for-concert-organizers
:: ============================================================

setlocal EnableDelayedExpansion

if "%~1"=="" (
    echo ERROR: LINE1 is required.
    echo Usage: make-button.bat LINE1 [--line2 LINE2] [--color COLOR] [--size PX] [--out DIR] [--filename NAME]
    exit /b 1
)

:: ---- Locate make-button.py ---------------------------------
set "PYFILE=%~dp0make-button.py"
if not exist "!PYFILE!" (
    echo ERROR: make-button.py not found next to this script.
    exit /b 1
)

:: ---- Pass all arguments straight through to Python ---------
::      argparse handles all flag parsing
python "!PYFILE!" %*

exit /b %errorlevel%
