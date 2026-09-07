# Development Log

## Architecture phase

- Defined multi-channel internet TV model with archive/VOD + live input.
- Playout layer separated from panel.
- Planned RTMP/SRT ingest, FFmpeg transcoding, ABR/HLS, CDN and EPG/scheduling.

## 1.2.0 generation

- Dason-based admin established.
- Channel/media/live/schedule management added.
- Public synchronized player established.
- API, users, ParsGreen, player settings and audit features established.

## 1.2.2 hardening

- PHP 7.4 URL sanitizer hotfix.
- System settings split into dedicated pages.
- Scheduler transactions, channel row locking and overlap protection.
- Login/OTP/session/upload/API/SSRF security hardening.

## 1.2.3 UI refinement

- Denser Dason dashboard.
- Persian server-synced header clock with seconds.
- Improved channel/current/next-program states and empty states.

## 1.2.4 auth slideshow control

- Independent off / on-without-image / on-with-image modes.
- Existing images preserved when visual mode is disabled.

## 1.2.6 — latest verified

- Fixed media save failure caused by upload path/directory conditions.
- Auto-create `media`, `logos`, `settings` upload directories safely.
- Check writable permission before saving and surface clear permission errors.
- Fresh installer creates/checks all three directories.
- JalaliDatePicker modal layering/binding fix recorded in release manifest.

## 2026-09-07 — Git migration

- Exact chat folder created as `Live TV Montazer`.
- Latest candidate verified by Library ordering and internal `VERSION`.
- Original artifact: `internet-tv-panel-v1.2.6.zip`.
- Original size: `17,806,252 bytes`.
- Original SHA-256: `f17da6d0b8837efe895ad74629e34dba3ae05e7c32fc7bec5954f984c0ca0276`.
- Extracted release contains `1,768` files; most extra files belong to bundled third-party Dason assets.
- Exact custom/application source identified as `37` files / `380,379` uncompressed bytes.
- Secret scan found no live credentials; `config.example.php` contains placeholders only.
- Direct large binary transfer was tested, detected as truncated by byte-size mismatch, and removed instead of being accepted.
- Critical files were also migrated into a readable convenience tree under `source/app/`.
- PlayerResolver and Schema byte-level mismatches caused by manual transfer were detected and corrected; comments/whitespace are treated as integrity-relevant when claiming exact copies.

## 2026-09-07 — Complete custom source correction

- User correctly reported that the earlier source transfer was incomplete.
- A complete custom/application archive was generated from the verified v1.2.6 tree containing exactly `37/37` project files.
- Complete custom archive size: `70,112 bytes`.
- Complete custom archive SHA-256: `2d50e6915c31e24012fc53b32227d4ab80190a6d12280983eb497eed942fc298`.
- Archive split into `9` decoded chunks to avoid connector truncation: eight 8,000-byte chunks plus one 6,112-byte chunk.
- All `9/9` Base64 Part files were committed to `source/full-custom-source.parts/`.
- Every remote Part was verified against its expected Base64 text size and Git blob SHA; all matched exactly.
- `PARTS_MANIFEST.sha256` records decoded SHA-256, decoded size and Git blob SHA for every Part.
- `REBUILD.md` records deterministic reconstruction steps for Linux/macOS and PowerShell.
- `SOURCE_MANIFEST.sha256` records the expected SHA-256 of all 37 individual custom source files.
- Status is now: **complete custom/application source 37/37 available and verifiable in Git**.
- Status is NOT: full original 17.8MB deployable release uploaded. The original Dason-bundled release remains tracked by size/SHA metadata and must not be called uploaded until a byte-identical binary is stored.
