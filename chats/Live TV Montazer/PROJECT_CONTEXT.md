# Project Context — Live TV Montazer

## Canonical identity

- Chat: `Live TV Montazer`
- Application: `Internet TV Panel`
- Current version: `1.2.6`
- Canonical original ZIP: `internet-tv-panel-v1.2.6.zip`
- SHA-256: `f17da6d0b8837efe895ad74629e34dba3ae05e7c32fc7bec5954f984c0ca0276`
- Size: `17,806,252 bytes`
- Extracted original file count: `1,768`
- Extracted size: approximately `51 MB`

## Runtime

- PHP 7.4+
- PDO MySQL
- MySQL / MariaDB with InnoDB
- cURL
- SOAP when ParsGreen SOAP mode is used
- Fileinfo recommended; `mime_content_type` fallback exists
- HTTPS strongly recommended
- `ffprobe` optional for server-side media inspection

## Main capabilities

- Multiple TV channels
- Media archive/library
- Live inputs: HLS / RTMP / SRT / WebRTC / other
- Linear schedule and timeline
- Quick Play / channel override for indefinite live takeover
- Public player in `watch.php`
- Player text/control settings
- Ads
- Tickers: breaking / advertising / prayer / general
- Prayer settings
- Users, roles and capabilities
- Password login + OTP login
- ParsGreen integration
- API Keys
- GET-only public API resources for channel/state
- Audit logs
- Viewer heartbeat/events
- Channel overrides

## Database tables

- users
- settings
- otp_codes
- login_attempts
- channels
- media
- live_inputs
- schedules
- ads
- tickers
- prayer_settings
- api_keys
- audit_logs
- viewer_events
- channel_overrides

## Streaming architecture

The administration panel is intentionally decoupled from the real Media Engine through:

- `StreamEngineInterface`
- `NullStreamEngine`
- `PlayerResolver`

Current phase is `panel_ready`. The planned server phase recorded by the project is:

1. Ubuntu LTS
2. Nginx
3. FFmpeg
4. MediaMTX
5. systemd Playout workers
6. Redis for queue/state when needed
7. HLS Origin
8. dedicated video storage
9. CDN
10. health checks and Prometheus-compatible metrics

The long-term broadcasting architecture combines archive/VOD and live input through a Playout engine, with RTMP/SRT ingest, transcoding, ABR/HLS delivery and scheduling/EPG.

## Security invariants

- CSRF on admin writes
- Login/password/OTP rate limiting
- Session ID regeneration after login
- HttpOnly + SameSite=Strict cookies and Secure on HTTPS
- Idle and absolute session timeouts
- Reduced account enumeration leakage
- MIME inspection for uploads
- Random upload filenames
- Script execution denied in uploads
- SSRF protections for remote probing/outbound endpoints
- ParsGreen secrets encrypted at rest
- Live input secret/password encrypted at rest
- API token stored as password hash; raw token shown once
- enum/role/protocol/API resource allowlists
- GET-only API with `no-store`
- security response headers

## Version authority warning

The root `README-FA.md` heading still says version `1.2.3` and `RELEASE-MANIFEST.txt` starts with `v1.2.2`, but both contain appended later sections including `v1.2.6`. The authoritative version is the root `VERSION` file, which is exactly `1.2.6`.
