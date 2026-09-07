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
- Original SHA-256 recorded: `f17da6d0b8837efe895ad74629e34dba3ae05e7c32fc7bec5954f984c0ca0276`.
- Secret scan found no live credentials; `config.example.php` contains placeholders only.
- Custom application source was repackaged without bulky Dason third-party tree/runtime uploads for Git development access.
