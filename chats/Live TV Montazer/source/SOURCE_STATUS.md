# Source Migration Status

## Verified baseline

- Version: `1.2.6`
- Original release: `internet-tv-panel-v1.2.6.zip`
- Original release size: `17,806,252 bytes`
- Original release SHA-256: `f17da6d0b8837efe895ad74629e34dba3ae05e7c32fc7bec5954f984c0ca0276`

## Readable source already stored in Git

The following v1.2.6 files have been copied exactly into `source/app/`:

- `.htaccess`
- `VERSION`
- `config.example.php`
- `bootstrap.php`
- `api.php`
- `logout.php`
- `NEXT-SERVER-PHASE.md`
- `THIRD_PARTY_NOTICES.txt`
- `app/Core/Database.php`
- `app/Core/Settings.php`
- `app/Core/Csrf.php`
- `app/Core/Crypto.php`
- `app/Core/View.php`
- `app/Core/Auth.php`
- `app/Core/Helpers.php`
- `app/Core/Security.php`
- `app/Services/Audit.php`
- `app/Services/MediaProbe.php`
- `app/Services/NullStreamEngine.php`
- `app/Services/StreamEngineInterface.php`
- `app/Services/PlayerResolver.php`
- `app/Services/SmsService.php`
- `database/schema.sql`

## Remaining custom source not yet copied file-by-file

These files are still represented by the verified local/original artifact and their expected SHA-256 values are recorded in `SOURCE_MANIFEST.sha256`:

- `README-FA.md`
- `RELEASE-MANIFEST.txt`
- `ajax.php`
- `app/Views/layouts/header.php`
- `app/Views/layouts/footer.php`
- `index.php`
- `install/index.php`
- `login.php`
- `watch.php`
- `public/assets/css/app.css`
- `public/assets/css/dason-panel.css`
- `public/assets/js/app.js`
- `public/assets/js/dason-panel.js`
- `public/assets/vendor/jalali/jalalidatepicker.min.css`

## Intentionally excluded from custom source migration

- `public/assets/dason/` — bundled third-party Dason asset tree
- `public/uploads/` — runtime/user upload directory

These exclusions do **not** change the identity of the original canonical release. The complete original artifact is defined only by the release size and SHA-256 above.

## Integrity rule

Before treating a migrated source file as exact, compare it against `SOURCE_MANIFEST.sha256`. Before treating a ZIP as the canonical release, compare both byte size and SHA-256 with `release/RELEASE_INFO.md`.
