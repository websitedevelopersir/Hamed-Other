# Development Guide

## Baseline

برای ادامه این چت، مبنا فایل زیر است:

`source/latest-known.html`

قبل از تغییر، این فایل‌ها هم خوانده شوند:

- `context/CHAT_CONTEXT.md`
- `context/DECISIONS.md`
- `docs/KNOWN_ISSUES.md`
- `logs/DEVELOPMENT_LOG.md`

## قواعدی که نباید شکسته شوند

- Desktop باید 5 تصویر را به صورت cyclic نمایش دهد.
- Mobile باید 3 تصویر را نمایش دهد.
- آیتم وسط همیشه Active/Center باشد.
- کلیک روی هر آیتم باید همان تصویر را به Center منتقل کند.
- Auto Play روی 3 ثانیه باقی بماند مگر کاربر صریحاً تغییر دهد.
- Hover باید Auto Play را Pause کند.
- Resize باید بدون Refresh صفحه اندازه‌ها را دوباره محاسبه کند.
- نسبت بصری سه سطح Center / Adjacent / Outer حفظ شود.
- Radius تصاویر `20px` حفظ شود مگر کاربر تغییر بخواهد.

## Responsive sizing

Desktop:

```js
const proportions5 = [0.55, 0.75, 1, 0.75, 0.55];
```

Mobile:

```js
const proportions3 = [0.75, 1, 0.75];
```

Aspect ratios:

```js
const centerRatio = 520 / 374;
const adjacentRatio = 348 / 250;
const outerRatio = 244 / 300;
```

## تصاویر

هیچ URL واقعی تصویر در Snapshot فعلی قابل بازیابی نیست. قبل از Release بعدی، URLهای واقعی باید جایگزین Placeholderها شوند.

## CTA مرکزی

درخواست قدیمی‌تر شامل Overlay `#212121CC` و دکمه `مشاهده کالکشن` بوده، اما در آخرین Responsive code حذف شده است. اگر کاربر ادامه همین ویژگی را خواست، CTA را روی Responsive baseline بازگردان؛ به نسخه قدیمی برنگرد.

## Update protocol

بعد از هر تغییر:

1. `source/latest-known.html` را به نسخه جدید تبدیل کن.
2. یک ورودی تاریخ‌دار در `logs/DEVELOPMENT_LOG.md` اضافه کن.
3. اگر تصمیم معماری/رفتاری عوض شد `context/DECISIONS.md` را بروزرسانی کن.
4. اگر باگ جدید یا محدودیت باقی ماند `docs/KNOWN_ISSUES.md` را بروزرسانی کن.
5. Commit message باید مشخص کند تغییر متعلق به Chat ID `693e5a1d-a50c-8328-90db-a7fb0017819b` است.
