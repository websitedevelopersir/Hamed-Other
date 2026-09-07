# Development Guide

## Before changing anything

1. Read `VERSION`.
2. Read `PROJECT_CONTEXT.md` and `context/DECISIONS.md`.
3. Read `CHANGELOG.md` and `docs/KNOWN_ISSUES.md`.
4. Use v1.2.6 as the only baseline.
5. Never infer the version from stale README/Manifest headings.
6. Do not commit a real `config.php`, DB credentials, ParsGreen keys, stream tokens or live secrets.

## Source baseline

Development source is stored as readable files under:

`source/app/`

This tree is copied from the verified v1.2.6 release. The large third-party Dason asset bundle and runtime/user uploads are intentionally not treated as custom application source:

- `public/assets/dason/` — third-party template/assets
- `public/uploads/` — runtime/user files

The original complete release remains identified by the immutable size/SHA recorded in `release/RELEASE_INFO.md`.

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

1. Update the root `VERSION` first.
2. Update both README and release manifest headings/sections so they no longer drift.
3. Build the full ZIP from the complete project including required third-party runtime assets.
4. Calculate SHA-256 and byte size.
5. Update this Git folder's `VERSION`, `CHANGELOG.md` and `PROJECT_CONTEXT.md`.
6. Replace/update the readable source tree under `source/app/`.
7. Record the new full Release metadata under `release/`.
8. Keep old versions through Git history, not as active baselines.

## Binary integrity rule

Do not call a ZIP the canonical release unless both its byte size and SHA-256 match the recorded artifact. A truncated connector upload or a repacked development-source ZIP is not the original release.
