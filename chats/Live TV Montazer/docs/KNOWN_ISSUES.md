# Known Issues / Watch List

## 1. Version headings are stale

The v1.2.6 release has `VERSION = 1.2.6`, but:

- `README-FA.md` title still says `نسخه 1.2.3`.
- `RELEASE-MANIFEST.txt` starts with `Internet TV Panel v1.2.2`.

Later sections include v1.2.6 changes. Future releases should synchronize all three locations.

## 2. Runtime CDN dependencies still exist

The exact v1.2.6 baseline still references external runtime assets in some places, including:

- `hls.js@1` from jsDelivr in `watch.php`
- GSAP `3.13.0` from jsDelivr
- JalaliDatePicker JS `1.0.0` from jsDelivr

The Dason assets themselves are bundled locally. Do not silently change these during migration; localize them only as an explicit development change and test playback/admin behavior afterward.

## 3. Media Engine is not connected yet

`system_phase` is panel-ready and `NullStreamEngine` is the safe default. Real 24/7 playout/transcode/ingest remains the next server phase.

## 4. Large third-party asset footprint

The original v1.2.6 ZIP is 17,806,252 bytes and extracts to about 51 MB / 1,768 files, mostly due to bundled Dason third-party assets. The Git `source/` archive intentionally excludes this large third-party tree but the canonical original release metadata is preserved separately.

## 5. Upload permissions are a known sensitive area

v1.2.6 specifically fixes upload directory creation and writable checks. Any refactor of upload paths/install logic must regression-test `media`, `logos` and `settings` directories.
