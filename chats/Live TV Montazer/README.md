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

## وضعیت Source

سورس اختصاصی برنامه اکنون **کامل 37/37** داخل Git ثبت شده و قابل بازسازی Byte-for-byte است.

مسیر مرجع کامل:

`source/full-custom-source.parts/`

مشخصات Source ZIP بازسازی‌شده:

- Size: `70,112 bytes`
- SHA-256: `2d50e6915c31e24012fc53b32227d4ab80190a6d12280983eb497eed942fc298`
- Files: `37/37 custom/application files`
- Parts: `9/9`, verified by size + Git blob SHA

راهنمای بازسازی:

`source/full-custom-source.parts/REBUILD.md`

Manifest:

- `source/full-custom-source.parts/PARTS_MANIFEST.sha256`
- `source/SOURCE_MANIFEST.sha256`

`source/app/` یک درخت خوانا برای دسترسی سریع به فایل‌های مهم است؛ معیار کامل بودن Source، آرشیو 37/37 بالاست.

## نکته Third-party

دو مسیر زیر عمداً جزو Custom/Application Source نیستند:

- `public/assets/dason/` — Third-party Dason assets
- `public/uploads/` — Runtime/User uploads

بنابراین 37/37 یعنی **تمام کد اختصاصی پروژه**؛ Release اصلی 1,768 فایل دارد چون Dason حجیم نیز داخل آن است.

## Release اصلی

نسخه کامل اصلی فقط زمانی معتبر است که دقیقاً با این مشخصات تطبیق کند:

- File: `internet-tv-panel-v1.2.6.zip`
- Size: `17,806,252 bytes`
- SHA-256: `f17da6d0b8837efe895ad74629e34dba3ae05e7c32fc7bec5954f984c0ca0276`

Binary کامل 17.8MB هنوز به‌صورت یک فایل یکپارچه در Git منتقل نشده و نباید Uploaded اعلام شود. وضعیت دقیق در `release/RELEASE_INFO.md` ثبت شده است.

## قبل از توسعه بعدی

به ترتیب بخوان:

1. `VERSION`
2. `PROJECT_CONTEXT.md`
3. `context/DECISIONS.md`
4. `CHANGELOG.md`
5. `docs/ARCHITECTURE.md`
6. `docs/DEVELOPMENT_GUIDE.md`
7. `docs/KNOWN_ISSUES.md`
8. `source/SOURCE_STATUS.md`

برای توسعه، baseline فقط `v1.2.6` است.
