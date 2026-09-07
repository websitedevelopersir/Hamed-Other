# Development Guide

## Before changing anything

1. Read `VERSION`.
2. Read `PROJECT_CONTEXT.md` and `context/DECISIONS.md`.
3. Read `CHANGELOG.md` and `docs/KNOWN_ISSUES.md`.
4. Read `source/SOURCE_STATUS.md`.
5. Use v1.2.6 as the only baseline.
6. Never infer the version from stale README/Manifest headings.
7. Do not commit a real `config.php`, DB credentials, ParsGreen keys, stream tokens or live secrets.

## Complete source baseline

The complete custom/application source of v1.2.6 is stored under:

`source/full-custom-source.parts/`

It contains all **37/37 custom application files**, split into 9 Base64-safe parts because large binary writes through the connector were previously truncated.

Reconstruct it using:

`source/full-custom-source.parts/REBUILD.md`

Expected reconstructed ZIP:

- Size: `70,112 bytes`
- SHA-256: `2d50e6915c31e24012fc53b32227d4ab80190a6d12280983eb497eed942fc298`
- Files: `37/37`

Every Part has a decoded SHA-256, decoded size and Git blob SHA in:

`source/full-custom-source.parts/PARTS_MANIFEST.sha256`

The SHA-256 of every source file is also stored in:

`source/SOURCE_MANIFEST.sha256`

## Readable convenience tree

`source/app/` contains directly readable copies of important files for quick inspection. It is a convenience tree, not the completeness authority. If a file is not directly expanded there, reconstruct the verified 37/37 source archive.

## Excluded from custom/application source

- `public/assets/dason/` — large third-party Dason template/assets
- `public/uploads/` — runtime/user uploads

These are not custom application code. The original full 17.8MB release includes Dason and is tracked separately by immutable release metadata.

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
2. Update README and release manifest headings/sections.
3. Start from the verified complete custom source; do not rebuild from memory/snippets.
4. Add required third-party Dason assets when producing the full deployable release.
5. Build the full ZIP.
6. Calculate SHA-256 and exact byte size.
7. Update `VERSION`, `CHANGELOG.md`, `PROJECT_CONTEXT.md` and `SOURCE_MANIFEST.sha256`.
8. Build a new complete custom-source archive and update all source Parts/manifest.
9. Record the new full Release metadata under `release/`.
10. Keep old versions through Git history, not as active baselines.

## Binary integrity rule

Never call an artifact canonical unless its size and SHA-256 match the recorded values. A truncated connector upload or a differently repacked ZIP is not the original release.
