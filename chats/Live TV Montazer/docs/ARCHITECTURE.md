# Architecture

## Entry points

- `index.php` — administration router/actions/pages
- `login.php` — password + OTP authentication
- `logout.php` — logout
- `watch.php` — public player
- `ajax.php` — admin/player AJAX endpoints including server clock
- `api.php` — token-authenticated GET-only API
- `bootstrap.php` — bootstrapping, config, autoload/core initialization
- `install/index.php` — installer wizard

## Core classes

- `Database`
- `Settings`
- `Security`
- `Auth`
- `Csrf`
- `Crypto`
- `Helpers`
- `View`

## Services

- `PlayerResolver` — resolves current linear/override playback state
- `StreamEngineInterface` — abstraction for external Media Engine
- `NullStreamEngine` — safe placeholder before server phase
- `MediaProbe` — media metadata / optional ffprobe
- `SmsService` — ParsGreen integration
- `Audit` — activity audit trail

## Views / UI

- Dason-based administration
- RTL
- Vazirmatn typography
- dedicated system settings pages
- restrained GSAP motion with `prefers-reduced-motion`

## Schedule model

- channel timezone is authoritative
- each scheduled media/live entry has a positive duration
- writes are serialized per channel
- overlap is rejected in a transaction
- reorder recalculates exact timeline
- indefinite live takeover belongs to Quick Play/channel override

## Player model

`watch.php` resolves channel state through PlayerResolver. The public player supports configurable controls/texts and HLS playback. Viewer heartbeat is validated and duplicate writes are suppressed within a short window.

## Server phase boundary

The panel must not absorb FFmpeg/MediaMTX worker logic directly. Playout workers, ingest, transcode, HLS origin and CDN belong to the separate streaming layer controlled through the engine abstraction.
