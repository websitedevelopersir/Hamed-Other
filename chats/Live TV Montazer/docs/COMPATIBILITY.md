# Compatibility & Dependencies

## Server

- PHP: 7.4+
- Database: MySQL / MariaDB, InnoDB
- Required: PDO MySQL, cURL
- Conditional: SOAP for ParsGreen SOAP mode
- Recommended: Fileinfo
- Optional: ffprobe
- Production: HTTPS strongly recommended

## Front-end/admin libraries in verified baseline

- Dason assets: bundled locally
- Bootstrap/Dason RTL assets: bundled with template tree
- Vazirmatn: used by admin UI
- GSAP: runtime CDN reference pinned to 3.13.0
- JalaliDatePicker CSS: local custom source includes it
- JalaliDatePicker JS: runtime CDN reference pinned to 1.0.0
- hls.js: runtime CDN reference uses major `@1`

## Lock files

No root `composer.json`, `composer.lock`, `package.json` or `package-lock.json` is used as the application dependency lock for this release. Do not invent lock files during migration.

## Data compatibility

The 1.2.x line avoids destructive schema migrations. Existing installations should preserve DB, config and uploads during file replacement. `login_attempts` can be auto-created on existing installs.
