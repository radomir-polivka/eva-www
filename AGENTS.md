# Web project Eva Bublova

A simple web presentation of a musician. 
Hosted at www.bublova.cz.

## Coding Conventions

Use UTF-8 encoding.

## Project Structure

There may be more copies of the web in the project directory:

1. Production web is always in the root project directory.
2. If /test subdirectory exists, it contains a test copy of the web under development.
3. Additional variants under test are in directories named /test-<n>, e.g.: /test-2.

Images (under /img/) are always stored in the root project directory and referenced
by absolute paths, so they are shared across all test variants without copying.

## Database Files

**Concert Database (`userconfig/concert.csv`):**
- Format: Pipe-separated values (|) with HTML formatting
- Structure: `date | time | location | description`
- Date format: YYYYMMDD
- Time format: HH:MM
- Descriptions can contain `<br/>` for line breaks and `<a href="">` for links

Example entry:
```
20260718 | 19:00 | Klášter u Nové Bystřice, kostel Nejsvětější Trojice | G. Frescobaldi, G. Böhm, J. K. Kerll, J. S. Bach, G. F. Händel, J. L. Krebs a W. A. Mozart na varhanním recitálu<br/>festival <a href="https://ceske-kulturni-slavnosti.webnode.cz/progam/" target="_blank">Vivat varhany</a>
```

## Workflow

- Edit local files in `/media/sf_eva-www/test`
- Upload to Web Server (see section How to upload to Web Server).
- Ask before creating a git commit.
- Ask before pushing changes to git remote.
- When test web is approved, copy the files from the chosen test variant to project root.

## How to upload to Web Server

FTP credentials are stored in `.env.local`.

Remote paths are the same as in the local project structure.

Upload to web server using FTP (example):
   ```bash
   source .env.local
   ftp -n $FTP_SERVER $FTP_PORT << EOF
   user $FTP_USERNAME $FTP_PASSWORD
   binary
   put /media/sf_eva-www/userconfig/concert.csv userconfig/concert.csv
   quit
   EOF
   ```

Verify upload by checking file listing on FTP server

