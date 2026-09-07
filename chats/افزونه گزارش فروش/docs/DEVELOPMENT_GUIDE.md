# Development Guide

## شروع هر توسعه

1. `VERSION` را بخوان.
2. `CHANGELOG.md` را بخوان.
3. `context/CHAT_CONTEXT.md` و `context/DECISIONS.md` را بخوان.
4. آخرین ZIP را از `release/` باز کن.
5. Plugin Header را با `VERSION` تطبیق بده.
6. سپس تغییرات را روی همان سورس اعمال کن.

## Canonical source

Artifact مرجع:

`release/mm-woo-sales-report-v1.1.0.zip`

SHA-256:

`07f6d1317d3a29039193276d46ceb25e2f92b2bcc689d7f8a1089173e78a989c`

فایل اصلی افزونه داخل ZIP:

`mm-woo-sales-report/mm-woo-sales-report.php`

کلاس اصلی Admin/Report:

`mm-woo-sales-report/includes/class-mmwsr-admin.php`

کلاس Export:

`mm-woo-sales-report/includes/class-mmwsr-export.php`

## موارد حساس هنگام تغییر

- HPOS و Query تاریخ را تست کن.
- تاریخ شروع بازه باید 00:00:00 و پایان بازه 23:59:59 را پوشش دهد.
- پریست هفته باید شنبه تا جمعه باقی بماند.
- پیش‌فرض Statusها را بدون درخواست کاربر تغییر نده.
- Transaction ID fallbackهای موجود را حفظ کن.
- CSV فارسی باید BOM UTF-8 داشته باشد.
- مبلغ، مالیات و تخفیف در Excel/CSV به صورت مقدار عددی بدون واحد پول صادر شوند.
- گزارش Products را با گزارش Orders یکی نکن؛ صفحه جداست.
- Performance optimizationهای Batch processing را حذف نکن.

## انتشار نسخه بعدی

مثلاً برای `1.1.1`:

1. Version Header و ثابت‌های نسخه را بروزرسانی کن.
2. ZIP نهایی را بساز و در `release/` جایگزین Artifact جاری کن.
3. `VERSION` را بروزرسانی کن.
4. `CHANGELOG.md` را بروزرسانی کن.
5. `logs/DEVELOPMENT_LOG.md` را با دلیل تغییر بروزرسانی کن.
6. در صورت تغییر رفتار، `context/DECISIONS.md` را بروزرسانی کن.
7. نسخه قدیمی را لازم نیست کنار نسخه جدید نگه داری؛ Git History آن را حفظ می‌کند.
