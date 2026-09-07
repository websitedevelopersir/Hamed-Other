# Changelog

## 1.2.6 — 2026-09-02

- رفع خطای «ذخیره فایل انجام نشد» در آپلود رسانه.
- ساخت امن و خودکار پوشه‌های `media`، `logos` و `settings` در صورت نبودن.
- بررسی writable بودن مسیر پیش از ذخیره و نمایش خطای Permission واضح.
- Installer در نصب تازه هر سه پوشه را می‌سازد و دسترسی نوشتن را بررسی می‌کند.
- اصلاح JalaliDatePicker داخل Modalهای Dason با z-index بالاتر و binding صریح `data-jdp`.
- مرجع JalaliDatePicker JS روی `@majidh1/jalalidatepicker 1.0.0` ثبت شده است.

## 1.2.4

- کنترل مستقل اسلایدهای صفحه ورود: خاموش کامل، فعال بدون تصویر، فعال با تصویر.
- تصاویر در حالت بدون تصویر حذف نمی‌شوند و فقط در خروجی نمایش داده نمی‌شوند.
- بدون تغییر دیتابیس؛ تنظیم در جدول Settings ذخیره می‌شود.

## 1.2.3

- بازطراحی Dashboard بر پایه Dason با چیدمان متراکم‌تر.
- ساعت هدر فارسی، سفید، دارای ثانیه و همگام با سرور.
- اکشن‌های هدر یک‌خطی.
- نمایش بهتر وضعیت شبکه و برنامه بعدی.
- Empty State برای جدول برنامه‌ها و کاهش فضای خالی.

## 1.2.2

- Hotfix سازگاری PHP 7.4 برای null-byte warning در `Helpers::url()`.
- اصلاح تولید URLهای Dason CSS/JS/images.
- تفکیک صفحات مدیریت سیستم: General، Login، ParsGreen، Player & Texts، Users، Activity Log.
- سخت‌گیری امنیتی Session/Login/OTP/Uploads/API/SSRF.
- دقیق‌تر شدن Scheduler با Row Lock، Transaction، overlap detection و Drag/Reorder امن.
- بدون Migration مخرب دیتابیس.

## 1.2.0 generation

- رابط مدیریتی کامل Dason.
- Media archive + channels + live input + schedules.
- Public player و موتور خطی PlayerResolver.
- API، کاربران، لاگ فعالیت، تنظیمات Player و ParsGreen.

> برای تشخیص نسخه جاری، همیشه `VERSION` داخل Artifact را بخوان؛ Header متن README/Manifest ممکن است عقب‌تر باشد.
