# Development Guide

## Before changing anything

1. Read `VERSION`.
2. Read `PROJECT_CONTEXT.md` and `context/DECISIONS.md`.
3. Read `CHANGELOG.md` and `docs/KNOWN_ISSUES.md`.
4. Use v1.2.6 as the only baseline.
5. Never infer the version from stale README/Manifest headings.
6. Do not commit a real `config.php`, DB credentials, ParsGreen keys, stream tokens or live secrets.

## Source baseline

`source/internet-tv-panel-v1.2.6-custom-source.zip`

This is extracted from the verified v1.2.6 release and excludes only:

- `public/assets/dason/` (large third-party template/assets)
- `public/uploads/` (runtime/user files)

No application PHP, database schema, custom CSS/JS, Jalali CSS, installer, public player, API or documentation was intentionally removed from this custom-source archive.

## Critical regression checklist

After every change test:

- PHP 7.4 syntax compatibility
- fresh install
- upgrade without deleting DB/config/uploads
- password login
- OTP request/verify rate limiting
- Session idle/absolute timeout
- channel CRUD
- media upload, especially missing upload directories and Permission errors
- logo/settings uploads
- live input secrets
- schedule overlap detection
- schedule reorder/drag
- Quick Play/override
- PlayerResolver current state
- public `watch.php`
- HLS playback
- player controls/text settings
- ParsGreen encrypted settings
- API key creation + one-time token display
- GET-only API
- audit log
- viewer heartbeat
- JalaliDatePicker inside Dason modals

## Release protocol

For a new version:

1. update the root `VERSION` first;
2. update both README and release manifest headings/sections so they no longer drift;
3. build the full ZIP;
4. calculate SHA-256 and size;
5. update this Git folder's VERSION/CHANGELOG/PROJECT_CONTEXT;
6. replace the current source archive;
7. keep old versions only through Git history, not as active baselines.
