# -*- coding: utf-8 -*-
"""
make-button.py  -  Generate a one- or two-line menu button PNG using GIMP 2.8.

The script builds a GIMP Script-Fu file, then calls GIMP in batch mode to
render it.  No XCF template is needed; the image is built entirely from
Script-Fu primitives.

Usage:
    python make-button.py LINE1 [--line2 TEXT] [--color #rrggbb] [--size PX]
                          [--letter-spacing PX]
                          [--unsharp-mask RADIUS,AMOUNT,THRESHOLD]
                          [--out DIR] [--filename NAME] [--scm-only FILE]

Arguments:
    LINE1                          Text for the first (or only) line  (required)
    --line2 TEXT                   Text for the second line; omit for single-line
    --color #rrggbb                Text color                (default: #cc5500)
    --size  PX                     Font size in pixels       (default: 14)
    --letter-spacing PX            Extra spacing between characters in pixels
                                   (default: 1)
    --unsharp-mask RADIUS,AMOUNT,THRESHOLD
                                   Apply unsharp mask after flattening.
                                   RADIUS: blur radius in pixels (float)
                                   AMOUNT: strength 0.0-1.0+ (float)
                                   THRESHOLD: 0-255 (int)
                                   Example: --unsharp-mask 2.0,0.5,0
                                   (default: off)
    --out   DIR                    Output directory          (default: ./out)
    --filename NAME                Output filename without extension
                                                             (default: button)
    --scm-only FILE                Write Script-Fu to FILE and exit without GIMP

Output:
    <DIR>/<NAME>.png

Button geometry (matches existing site buttons):
    Single-line : width=auto x 18 px, text right-aligned
    Two-line    : width=auto x 31 px, each line right-aligned,
                  row1 at y=0, row2 at y=13
    Background  : #d8d8d8
    Font        : Tahoma, hinting on, auto-hinter on, antialiasing on
"""

import argparse
import os
import re
import subprocess
import sys
import tempfile

# ---------------------------------------------------------------------------
# Constants
# ---------------------------------------------------------------------------
GIMP_EXE        = r'D:\app-portable\gimp\App\gimp\bin\gimp-2.8.exe'
BG_COLOR        = (0xd8, 0xd8, 0xd8)   # #d8d8d8
FONT_NAME       = 'Tahoma'
ROW2_Y          = 13    # vertical offset of second line
HEIGHT_1LINE    = 18    # image height for single-line buttons
HEIGHT_2LINE    = 31    # image height for two-line buttons


# ---------------------------------------------------------------------------
# Argument parsing
# ---------------------------------------------------------------------------
def parse_args():
    parser = argparse.ArgumentParser(
        description='Generate a one- or two-line menu button PNG via GIMP 2.8.')
    parser.add_argument('line1',
                        help='First (or only) line of button text')
    parser.add_argument('--line2', default=None, metavar='TEXT',
                        help='Second line of button text (omit for single-line)')
    parser.add_argument('--color', default='#cc5500', metavar='#rrggbb',
                        help='Text color (default: #cc5500)')
    parser.add_argument('--size', default=14, type=int, metavar='PX',
                        help='Font size in pixels (default: 14)')
    parser.add_argument('--letter-spacing', default=1, type=int, metavar='PX',
                        help='Extra letter spacing in pixels (default: 1)')
    parser.add_argument('--unsharp-mask', default=None, metavar='RADIUS,AMOUNT,THRESHOLD',
                        help='Apply unsharp mask, e.g. 2.0,0.5,0 (default: off)')
    parser.add_argument('--out', default='./out', metavar='DIR',
                        help='Output directory (default: ./out)')
    parser.add_argument('--filename', default='button', metavar='NAME',
                        help='Output filename without extension (default: button)')
    parser.add_argument('--scm-only', metavar='FILE',
                        help='Write Script-Fu to FILE and exit without running GIMP')
    return parser.parse_args()


def parse_color(hex_color):
    """Return (r, g, b) tuple from a #rrggbb string."""
    m = re.fullmatch(r'#?([0-9a-fA-F]{6})', hex_color.strip())
    if not m:
        sys.exit('ERROR: color must be in #rrggbb format, got: %s' % hex_color)
    h = m.group(1)
    return int(h[0:2], 16), int(h[2:4], 16), int(h[4:6], 16)


def parse_unsharp_mask(s):
    """Parse 'radius,amount,threshold' string. Returns (radius, amount, threshold)."""
    try:
        parts = s.split(',')
        if len(parts) != 3:
            raise ValueError
        return float(parts[0]), float(parts[1]), int(parts[2])
    except (ValueError, AttributeError):
        sys.exit('ERROR: --unsharp-mask must be RADIUS,AMOUNT,THRESHOLD e.g. 2.0,0.5,0')


# ---------------------------------------------------------------------------
# Script-Fu generation helpers
# ---------------------------------------------------------------------------
def scm_escape(text):
    """Escape a Python string for embedding in a Script-Fu string literal."""
    return text.replace('\\', '\\\\').replace('"', '\\"')


def scm_measure_block(img_var, lay_var, tw_var, text, font_size, letter_spacing):
    """Script-Fu let* bindings: create a scratch image, render text, bind tw_var to width.
    letter_spacing is applied so measurement accounts for extra spacing."""
    lines = [
        '   (%s  (car (gimp-image-new 1 1 RGB)))' % img_var,
        '   (%s  (car (gimp-layer-new %s 1 1 RGB-IMAGE "m" 100 0)))' % (lay_var, img_var),
        '   (dummy-%s-ins (gimp-image-insert-layer %s %s 0 -1))' % (lay_var, img_var, lay_var),
        '   (dummy-%s-txt (car (gimp-text-fontname %s -1 0 0 "%s" 0 TRUE %d UNIT-PIXEL "%s")))' % (
            tw_var, img_var, text, font_size, FONT_NAME),
    ]
    if letter_spacing != 0:
        lines += [
            '   (dummy-%s-ls (gimp-text-layer-set-letter-spacing dummy-%s-txt %d))' % (
                tw_var, tw_var, letter_spacing),
        ]
    lines += [
        '   (%s (car (gimp-drawable-width dummy-%s-txt)))' % (tw_var, tw_var),
    ]
    return lines


def scm_setup_text_layer(tl_var, color_rgb, letter_spacing):
    """Return Script-Fu lines to configure a text layer: color, hinting, letter-spacing."""
    r, g, b = color_rgb
    lines = [
        '   (dummy-%s-color (gimp-text-layer-set-color %s (list %d %d %d)))' % (tl_var, tl_var, r, g, b),
        '   (dummy-%s-hint  (gimp-text-layer-set-hinting %s TRUE TRUE))' % (tl_var, tl_var),
    ]
    if letter_spacing != 0:
        lines += [
            '   (dummy-%s-ls    (gimp-text-layer-set-letter-spacing %s %d))' % (
                tl_var, tl_var, letter_spacing),
        ]
    return lines


def scm_post_flatten(unsharp):
    """Return Script-Fu lines for post-processing after gimp-image-flatten:
    optional unsharp mask. Assumes the flattened layer is bound to 'final-lay'
    and image to 'img'."""
    lines = []
    if unsharp is not None:
        radius, amount, threshold = unsharp
        lines += [
            '   (dummy-usm (plug-in-unsharp-mask RUN-NONINTERACTIVE img final-lay %.6g %.6g %d))' % (
                radius, amount, threshold),
        ]
    return lines


def build_scm_1line(line1, color_rgb, font_size, out_png_fwd,
                    letter_spacing=1, unsharp=None):
    """Script-Fu for a single-line button."""
    r, g, b    = color_rgb
    br, bg, bb = BG_COLOR
    l1         = scm_escape(line1)
    h          = HEIGHT_1LINE

    lines = []
    a = lines.append

    a('(let*')
    a('  (')
    lines += scm_measure_block('img-m1', 'lay-m1', 'tw1', l1, font_size, letter_spacing)
    a('   (img    (car (gimp-image-new tw1 %d RGB)))' % h)
    a('   (bg-lay (car (gimp-layer-new img tw1 %d RGB-IMAGE "Background" 100 0)))' % h)
    a('   (dummy-bg-ins  (gimp-image-insert-layer img bg-lay 0 -1))')
    a('   (dummy-bg-fill (begin')
    a('      (gimp-context-set-foreground (list %d %d %d))' % (br, bg, bb))
    a('      (gimp-edit-fill bg-lay FOREGROUND-FILL)))')
    a('   (tl1   (car (gimp-text-fontname img -1 0 0 "%s" 0 TRUE %d UNIT-PIXEL "%s")))' % (
        l1, font_size, FONT_NAME))
    lines += scm_setup_text_layer('tl1', color_rgb, letter_spacing)
    a('   (dummy-tl1-pos  (gimp-layer-set-offsets tl1 (- tw1 (car (gimp-drawable-width tl1))) 0))')
    a('   (dummy-flat (gimp-image-flatten img))')
    a('   (final-lay  (car (gimp-image-get-active-drawable img)))')
    lines += scm_post_flatten(unsharp)
    a('  )')
    a('  (begin')
    a('    (file-png-save RUN-NONINTERACTIVE img final-lay')
    a('      "%s" "%s" 0 9 1 1 1 1 1)' % (out_png_fwd, out_png_fwd))
    a('    (gimp-image-delete img)')
    a('    (gimp-image-delete img-m1)')
    a('    (gimp-quit 0)')
    a('  )')
    a(')')

    return '\n'.join(lines) + '\n'


def build_scm_2line(line1, line2, color_rgb, font_size, out_png_fwd,
                    letter_spacing=1, unsharp=None):
    """Script-Fu for a two-line button."""
    r, g, b    = color_rgb
    br, bg, bb = BG_COLOR
    l1         = scm_escape(line1)
    l2         = scm_escape(line2)
    h          = HEIGHT_2LINE

    lines = []
    a = lines.append

    a('(let*')
    a('  (')
    lines += scm_measure_block('img-m1', 'lay-m1', 'tw1', l1, font_size, letter_spacing)
    lines += scm_measure_block('img-m2', 'lay-m2', 'tw2', l2, font_size, letter_spacing)
    a('   (tw     (max tw1 tw2))')
    a('   (img    (car (gimp-image-new tw %d RGB)))' % h)
    a('   (bg-lay (car (gimp-layer-new img tw %d RGB-IMAGE "Background" 100 0)))' % h)
    a('   (dummy-bg-ins  (gimp-image-insert-layer img bg-lay 0 -1))')
    a('   (dummy-bg-fill (begin')
    a('      (gimp-context-set-foreground (list %d %d %d))' % (br, bg, bb))
    a('      (gimp-edit-fill bg-lay FOREGROUND-FILL)))')
    a('   (tl1   (car (gimp-text-fontname img -1 0 0 "%s" 0 TRUE %d UNIT-PIXEL "%s")))' % (
        l1, font_size, FONT_NAME))
    lines += scm_setup_text_layer('tl1', color_rgb, letter_spacing)
    a('   (dummy-tl1-pos   (gimp-layer-set-offsets tl1 (- tw (car (gimp-drawable-width tl1))) 0))')
    a('   (tl2   (car (gimp-text-fontname img -1 0 %d "%s" 0 TRUE %d UNIT-PIXEL "%s")))' % (
        ROW2_Y, l2, font_size, FONT_NAME))
    lines += scm_setup_text_layer('tl2', color_rgb, letter_spacing)
    a('   (dummy-tl2-pos   (gimp-layer-set-offsets tl2 (- tw (car (gimp-drawable-width tl2))) %d))' % ROW2_Y)
    a('   (dummy-flat (gimp-image-flatten img))')
    a('   (final-lay  (car (gimp-image-get-active-drawable img)))')
    lines += scm_post_flatten(unsharp)
    a('  )')
    a('  (begin')
    a('    (file-png-save RUN-NONINTERACTIVE img final-lay')
    a('      "%s" "%s" 0 9 1 1 1 1 1)' % (out_png_fwd, out_png_fwd))
    a('    (gimp-image-delete img)')
    a('    (gimp-image-delete img-m1)')
    a('    (gimp-image-delete img-m2)')
    a('    (gimp-quit 0)')
    a('  )')
    a(')')

    return '\n'.join(lines) + '\n'


# ---------------------------------------------------------------------------
# GIMP stderr noise filter
# ---------------------------------------------------------------------------
_GIMP_NOISE = (
    'zlib1.dll',
    'gimp_wire_read',
    'No module named gimp',
    'from gimpfu import',
    'import gimp',
    'Traceback (most recent call last)',
    'File "',
    'ImportError:',
    'Trying to register gtype',
)

def _is_gimp_noise(line):
    s = line.strip()
    return any(pat in s for pat in _GIMP_NOISE)


# ---------------------------------------------------------------------------
# GIMP runner
# ---------------------------------------------------------------------------
def run_gimp(scm_content, scm_only_path=None):
    """Write scm_content to a temp file and run GIMP, or dump to scm_only_path."""
    if scm_only_path:
        with open(scm_only_path, 'w', encoding='utf-8') as f:
            f.write(scm_content)
        print('Script-Fu written to: %s' % scm_only_path)
        return

    fd, scm_path = tempfile.mkstemp(suffix='.scm', prefix='make-button-')
    try:
        with os.fdopen(fd, 'w', encoding='utf-8') as f:
            f.write(scm_content)

        scm_fwd = scm_path.replace('\\', '/')
        print('Running GIMP...')
        result = subprocess.run(
            [GIMP_EXE, '-i', '--no-splash',
             '-b', '(load "%s")' % scm_fwd,
             '-b', '(gimp-quit 0)'],
            stderr=subprocess.PIPE,
        )
        if result.stderr:
            for line in result.stderr.decode('utf-8', errors='replace').splitlines():
                if _is_gimp_noise(line):
                    continue
                print(line, file=sys.stderr)
        if result.returncode != 0:
            sys.exit('ERROR: GIMP exited with code %d' % result.returncode)
    finally:
        try:
            os.unlink(scm_path)
        except OSError:
            pass


# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------
def main():
    args = parse_args()

    color_rgb  = parse_color(args.color)
    out_dir    = os.path.abspath(args.out)
    os.makedirs(out_dir, exist_ok=True)
    out_png    = os.path.join(out_dir, args.filename + '.png')
    out_fwd    = out_png.replace('\\', '/')
    unsharp    = parse_unsharp_mask(args.unsharp_mask) if args.unsharp_mask else None

    if args.line2:
        scm = build_scm_2line(args.line1, args.line2, color_rgb, args.size, out_fwd,
                              args.letter_spacing, unsharp)
    else:
        scm = build_scm_1line(args.line1, color_rgb, args.size, out_fwd,
                              args.letter_spacing, unsharp)

    run_gimp(scm, scm_only_path=args.scm_only)

    if not args.scm_only:
        if os.path.exists(out_png):
            print('OK: %s' % out_png)
        else:
            sys.exit('ERROR: output file was not created: %s' % out_png)


if __name__ == '__main__':
    main()
