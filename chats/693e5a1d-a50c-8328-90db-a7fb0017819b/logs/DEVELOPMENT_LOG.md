# Development Log

## 2025-07-22 — Initial gallery request

- درخواست گالری سفارشی Elementor با 5 تصویر.
- تصویر مرکزی بزرگ‌تر از بقیه.
- Center: `374×520`.
- Adjacent: `250×348`.
- Outer: `300×244`.
- کلیک هر تصویر برای انتقال به مرکز.

## 2025-07-26 — Styling and interaction refinements

- فونت `YekanbakhR`.
- Radius تصاویر `20px`.
- Auto Play در روند گفتگو از 2 ثانیه به 3 ثانیه تغییر کرد.
- Overlay Hover با `#212121CC` مطرح شد.
- CTA مرکزی با متن `مشاهده کالکشن` مطرح شد.
- در یکی از نسخه‌های میانی duplicate `galleryTrack.appendChild(item)` وجود داشت.
- برای Animation از force reflow / render transition استفاده شد.

## 2025-08-12 — Responsive baseline

- تلاش Responsive قبلی رد شد.
- نسخه جدید باید دقیقاً منطق Responsive مورد قبول را حفظ کند.
- Desktop: 5 آیتم.
- Mobile: 3 آیتم.
- `proportions5=[0.55,0.75,1,0.75,0.55]`.
- `proportions3=[0.75,1,0.75]`.
- نسبت‌های `520/374`، `348/250` و `244/300`.
- محاسبه عرض‌ها بر اساس Container و Gap.
- Resize باعث Re-render می‌شود.
- Auto Play هر `3000ms`.
- Hover Auto Play را Pause/Resume می‌کند.
- آخرین کد بازیابی‌شده CTA مرکزی را دیگر در Markup ندارد.
- URL تصاویر در نسخه بازیابی‌شده ناقص بوده است.

## 2026-09-07 — Git migration

- گفتگوی ChatGPT به Repo `websitedevelopersir/Hamed-Other` منتقل شد.
- Chat ID به عنوان کلید پوشه حفظ شد.
- Context، Decisions، Known Issues و Development Guide اضافه شدند.
- آخرین منطق قابل بازیابی به `source/latest-known.html` تبدیل شد.
- URL تصاویر عمداً جعل نشد و به صورت Placeholder باقی ماند.
