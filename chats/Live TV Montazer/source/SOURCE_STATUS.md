# Source Migration Status

## نتیجه نهایی

**Complete custom/application source: 37/37 files — VERIFIED**

نسخه اختصاصی پروژه از v1.2.6 اکنون به‌طور کامل داخل Git قابل بازسازی است و هر ۹ Part از نظر اندازه و Git blob SHA با فایل محلی تطبیق داده شده است.

## Verified baseline

- Version: `1.2.6`
- Original full release: `internet-tv-panel-v1.2.6.zip`
- Original full release size: `17,806,252 bytes`
- Original full release SHA-256: `f17da6d0b8837efe895ad74629e34dba3ae05e7c32fc7bec5954f984c0ca0276`

## Complete custom/application source archive

Path:

`full-custom-source.parts/`

- Parts: `9/9`
- Part decoded sizes: `8 × 8,000 bytes + 1 × 6,112 bytes`
- Reconstructed ZIP size: `70,112 bytes`
- Reconstructed ZIP SHA-256: `2d50e6915c31e24012fc53b32227d4ab80190a6d12280983eb497eed942fc298`
- Application/custom files in archive: `37/37`
- Original uncompressed custom-source bytes: `380,379`

Integrity details:

- `full-custom-source.parts/PARTS_MANIFEST.sha256`
- `full-custom-source.parts/REBUILD.md`
- `SOURCE_MANIFEST.sha256`

## What the complete custom archive contains

تمام فایل‌های اختصاصی پروژه، شامل:

- `.htaccess`
- `VERSION`
- `README-FA.md`
- `RELEASE-MANIFEST.txt`
- `THIRD_PARTY_NOTICES.txt`
- `NEXT-SERVER-PHASE.md`
- `bootstrap.php`
- `index.php`
- `ajax.php`
- `api.php`
- `login.php`
- `logout.php`
- `watch.php`
- `install/index.php`
- تمام `app/Core/*`
- تمام `app/Services/*`
- `app/Views/layouts/header.php`
- `app/Views/layouts/footer.php`
- `database/schema.sql`
- Custom CSS/JS
- JalaliDatePicker CSS

## Readable source tree

`source/app/` نیز برای خواندن سریع بخشی از فایل‌های مهم را مستقیم نگه می‌دارد. این درخت برای Convenience است و مرجع کامل بودن Source نیست.

اگر فایلی مستقیم زیر `source/app/` دیده نشد، نسخه دقیق آن داخل آرشیو کامل 37/37 در `full-custom-source.parts/` وجود دارد و با `REBUILD.md` بازسازی می‌شود.

## Intentionally excluded from custom/application source

این دو مسیر جزء کد اختصاصی پروژه نیستند و عمداً در Custom Source archive قرار نگرفته‌اند:

- `public/assets/dason/` — bundled third-party Dason template/assets
- `public/uploads/` — runtime/user uploads

بنابراین عبارت **37/37 Complete** به معنی «تمام سورس اختصاصی برنامه» است، نه تمام 1,768 فایل Release که شامل Third-party Dason نیز می‌شود.

## Original full release status

فایل کامل اصلی 17.8MB هنوز به‌عنوان Binary یکپارچه در Git ثبت نشده است. مشخصات قطعی آن در `../release/RELEASE_INFO.md` ثبت شده و تا زمانی که Size + SHA-256 دقیقاً تطبیق نکند نباید Uploaded/Verified اعلام شود.

## Integrity rule

- Custom Source کامل فقط اگر ZIP بازسازی‌شده SHA-256 برابر `2d50e691...fc298` داشته باشد معتبر است.
- Original Full Release فقط اگر Size=`17,806,252` و SHA-256=`f17da6d0...a0276` باشد معتبر است.
