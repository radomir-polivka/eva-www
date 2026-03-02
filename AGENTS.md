WWW Eva Bublova

A simple web presentation of a musician.

Use UTF-8 encoding.

## Web Server Configuration

FTP credentials are stored in `.env.local` (local-only, not committed to remote).

Remote paths are the same as in the local project structure.

## Database Files

**Concert Database (`userconfig/concert.csv`):**
- Format: Pipe-separated values (|) with HTML formatting
- Structure: `date | time | location | description`
- Date format: YYYYMMDD
- Time format: HH:MM
- Descriptions can contain `<br/>` for line breaks and `<a href="">` for links
- NO entry numbers in the CSV (the numbered lines in the file are display artifacts)

Example entry:
```
20260718 | 19:00 | Klášter u Nové Bystřice, kostel Nejsvětější Trojice | G. Frescobaldi, G. Böhm, J. K. Kerll, J. S. Bach, G. F. Händel, J. L. Krebs a W. A. Mozart na varhanním recitálu<br/>festival <a href="https://ceske-kulturni-slavnosti.webnode.cz/progam/" target="_blank">Vivat varhany</a>
```

## Workflow

1. Edit local files in `/media/sf_eva-www/`
2. Commit changes to git (optional but recommended)
3. Upload to web server using FTP:
   ```bash
   source .env.local
   ftp -n $FTP_SERVER $FTP_PORT << EOF
   user $FTP_USERNAME $FTP_PASSWORD
   binary
   put /media/sf_eva-www/userconfig/concert.csv userconfig/concert.csv
   quit
   EOF
   ```
4. Verify upload by checking file listing on FTP server
