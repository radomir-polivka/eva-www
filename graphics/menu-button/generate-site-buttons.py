# -*- coding: utf-8 -*-
"""
generate-site-buttons.py  -  Generate the complete set of menu button PNGs
                              for the Eva Bublova website.

Calls make-button.py once per button per state.

Usage:
    python generate-site-buttons.py [--color-active #rrggbb]
                                    [--color-inactive #rrggbb]
                                    [--size PX]
                                    [--out DIR]

Arguments:
    --color-active   #rrggbb    Active text color   (default: #f76900)
    --color-inactive #rrggbb    Inactive text color (default: #301c28)
    --size PX                   Font size in pixels (default: 14)
    --out  DIR                  Staging output directory
                                (default: ./out/site-buttons next to this script)

Output structure:
    <DIR>/active/   <filename>.png   (one per button)
    <DIR>/inactive/ <filename>.png

Button list is derived from $menu_cz and $menu_en in www_root/include/page.php.
"""

import argparse
import os
import subprocess
import sys

# ---------------------------------------------------------------------------
# Button definitions
# Derived from $menu_cz and $menu_en in www_root/include/page.php.
# Format: (filename, line1)        for single-line buttons
#         (filename, line1, line2) for two-line buttons
# ---------------------------------------------------------------------------
BUTTONS = [
    # Czech menu
    ('zivotopis',               u'\u017divotopis'),
    ('koncerty',                u'Koncerty'),
    ('cd-mp3',                  u'CD, mp3'),
    ('pozitiv',                 u'Varhann\u00ed pozitiv'),
    ('foto',                    u'Foto'),
    ('pro-poradatele',          u'Pro po\u0159adatele'),
    ('kontakt',                 u'Kontakt'),
    # English menu
    ('curriculum-vitae',        u'Curriculum vitae'),
    ('concerts',                u'Concerts'),
    ('positive-organ',          u'Positive organ'),
    ('photo-gallery',           u'Photo gallery'),
    ('for-concert-organizers',  u'For concert', u'organizers'),
    ('contact',                 u'Contact'),
]

STATES = [
    ('active',   '#f76900'),
    ('inactive', '#301c28'),
]


# ---------------------------------------------------------------------------
# Argument parsing
# ---------------------------------------------------------------------------
def parse_args():
    script_dir = os.path.dirname(os.path.abspath(__file__))
    default_out = os.path.join(script_dir, 'out', 'site-buttons')
    parser = argparse.ArgumentParser(
        description='Generate all site menu button PNGs by calling make-button.py.')
    parser.add_argument('--color-active',   default='#f76900', metavar='#rrggbb',
                        help='Active text color (default: #f76900)')
    parser.add_argument('--color-inactive', default='#301c28', metavar='#rrggbb',
                        help='Inactive text color (default: #301c28)')
    parser.add_argument('--size', default=14, type=int, metavar='PX',
                        help='Font size in pixels (default: 14)')
    parser.add_argument('--letter-spacing', default=1, type=int, metavar='PX',
                        help='Extra letter spacing in pixels (default: 1)')
    parser.add_argument('--unsharp-mask', default=None, metavar='RADIUS,AMOUNT,THRESHOLD',
                        help='Apply unsharp mask, e.g. 2.0,0.5,0 (default: off)')
    parser.add_argument('--out', default=default_out, metavar='DIR',
                        help='Staging output directory')
    return parser.parse_args()


# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------
def main():
    args = parse_args()
    out_dir = os.path.abspath(args.out)

    state_colors = {
        'active':   args.color_active,
        'inactive': args.color_inactive,
    }

    script_dir  = os.path.dirname(os.path.abspath(__file__))
    make_button = os.path.join(script_dir, 'make-button.py')

    total   = len(BUTTONS) * len(STATES)
    done    = 0
    errors  = 0

    print('Output directory : %s' % out_dir)
    print('Active color     : %s' % args.color_active)
    print('Inactive color   : %s' % args.color_inactive)
    print('Font size        : %d px' % args.size)
    if args.letter_spacing != 1:
        print('Letter spacing   : %d px' % args.letter_spacing)
    if args.unsharp_mask:
        print('Unsharp mask     : %s' % args.unsharp_mask)
    print('Buttons          : %d x %d states = %d PNGs' % (
        len(BUTTONS), len(STATES), total))
    print()

    for state, _ in STATES:
        color   = state_colors[state]
        out_sub = os.path.join(out_dir, state)
        os.makedirs(out_sub, exist_ok=True)

        for entry in BUTTONS:
            fname = entry[0]
            line1 = entry[1]
            line2 = entry[2] if len(entry) == 3 else None

            cmd = [
                sys.executable, make_button,
                line1,
                '--color', color,
                '--size',  str(args.size),
                '--out',   out_sub,
                '--filename', fname,
            ]
            if line2:
                cmd += ['--line2', line2]
            if args.letter_spacing != 1:
                cmd += ['--letter-spacing', str(args.letter_spacing)]
            if args.unsharp_mask:
                cmd += ['--unsharp-mask', args.unsharp_mask]

            label = '%s/%s' % (state, fname)
            print('[%d/%d] %s' % (done + 1, total, label))

            result = subprocess.run(cmd, stderr=subprocess.PIPE, stdout=subprocess.PIPE)

            stdout = result.stdout.decode('utf-8', errors='replace').strip()
            stderr = result.stderr.decode('utf-8', errors='replace').strip()
            if stdout:
                print('  ' + stdout)
            if stderr:
                print('  STDERR: ' + stderr, file=sys.stderr)
            if result.returncode != 0:
                print('  ERROR: make-button.py exited with code %d' % result.returncode,
                      file=sys.stderr)
                errors += 1
            else:
                done += 1

    print()
    if errors:
        print('Done with %d error(s). %d/%d PNGs generated.' % (errors, done, total))
        sys.exit(1)
    else:
        print('Done. %d PNGs generated in %s' % (done, out_dir))


if __name__ == '__main__':
    main()
