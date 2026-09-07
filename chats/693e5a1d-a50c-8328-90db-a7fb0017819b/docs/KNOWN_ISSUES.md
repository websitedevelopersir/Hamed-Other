# Known Issues

## 1. Missing real image URLs

آخرین کد بازیابی‌شده از گفتگو URLهای ناقص/Placeholder به شکل زیر داشت:

`https://mohammad.websitestest.ir/...`

این URLها نباید به عنوان Source واقعی Production استفاده شوند.

## 2. Hover CTA regression

در نسخه‌های قبلی گفتگو برای تصویر مرکزی:

- Overlay با `#212121CC`
- دکمه `مشاهده کالکشن`

درخواست شده بود. در آخرین نسخه Responsive بازیابی‌شده، Markup دکمه دیگر وجود ندارد. هنگام ادامه توسعه باید مشخص شود CTA برگردد و با Responsive baseline ادغام شود.

## 3. Historical duplicate append

در یکی از وضعیت‌های قبلی کد، `galleryTrack.appendChild(item)` به صورت تکراری دیده شده بود. این مورد نباید دوباره وارد baseline شود.

## 4. Exact last inline source was not stored as downloadable artifact

خروجی نهایی گفتگو یک فایل مستقل ذخیره‌شده نبود. بنابراین `source/latest-known.html` یک Snapshot توسعه‌ای بر مبنای آخرین منطق قابل بازیابی است و این وضعیت در Header فایل صریحاً ذکر شده است.

## 5. Responsive behavior is mandatory

یک تلاش Responsive قبلی توسط کاربر رد شده بود. هر توسعه بعدی باید رفتار Responsive نسخه نهایی را حفظ کند و نباید به نسخه ثابت Desktop برگردد.
