# Live TV Montazer

- Chat title: `Live TV Montazer`
- Project identity: `Internet TV Panel`
- Current verified version: `1.2.6`
- Platform: standalone PHP + MySQL/MariaDB
- Canonical Library artifact: `internet-tv-panel-v1.2.6.zip`
- Canonical artifact size: `17,806,252 bytes`
- Canonical artifact SHA-256: `f17da6d0b8837efe895ad74629e34dba3ae05e7c32fc7bec5954f984c0ca0276`
- Internal version proof: `VERSION = 1.2.6`

## هدف پروژه

پنل مدیریت تلویزیون اینترنتی چندشبکه‌ای با آرشیو رسانه، ورودی زنده، جدول پخش خطی، پخش فوری، Player عمومی، API، مدیریت کاربران و احراز هویت، Ticker/تبلیغات/اوقات شرعی و آمادگی اتصال به Media Server واقعی.

## وضعیت فعلی

نسخه `1.2.6` جدیدترین نسخه پیدا و از داخل فایل واقعی بررسی شده است. برای توسعه بعدی، `VERSION` داخل سورس مرجع نهایی نسخه است؛ تیتر نسخه در بعضی فایل‌های README/Manifest قدیمی مانده و نباید مبنای انتخاب نسخه قرار گیرد.

قبل از هر تغییر این فایل‌ها خوانده شوند:

- `PROJECT_CONTEXT.md`
- `CHANGELOG.md`
- `context/DECISIONS.md`
- `docs/ARCHITECTURE.md`
- `docs/DEVELOPMENT_GUIDE.md`
- `docs/KNOWN_ISSUES.md`

## سورس قابل توسعه در Git

مرجع توسعه خوانا در مسیر زیر نگهداری می‌شود:

`source/app/`

کدهای Core، Security، Auth، Database، Settings، API، PlayerResolver، ParsGreen/SMS، Schema و فایل‌های اصلی پروژه از نسخه واقعی 1.2.6 به این مسیر منتقل می‌شوند. مجموعه حجیم Third-party Dason و `public/uploads/` جزء سورس اختصاصی پروژه محسوب نمی‌شوند.

## Release اصلی

نسخه کامل اصلی **فقط** زمانی معتبر است که دقیقاً با مشخصات زیر تطبیق کند:

- File: `internet-tv-panel-v1.2.6.zip`
- Size: `17,806,252 bytes`
- SHA-256: `f17da6d0b8837efe895ad74629e34dba3ae05e7c32fc7bec5954f984c0ca0276`

وضعیت انتقال Binary کامل در `release/RELEASE_INFO.md` ثبت شده است. هیچ ZIP ناقص یا Repacked نباید به‌عنوان Release اصلی معرفی شود.
