# Source Index

سورس کامل نسخه مرجع داخل ZIP زیر نگهداری می‌شود:

`../release/mm-woo-sales-report-v1.1.0.zip`

## ساختار تأییدشده داخل ZIP

```text
mm-woo-sales-report/
├── mm-woo-sales-report.php                # Plugin header / bootstrap
├── includes/
│   ├── class-mmwsr-admin.php              # Admin UI, filters, reports, product aggregation, exports
│   └── class-mmwsr-export.php             # CSV export logic
└── assets/
    ├── admin.css
    └── admin.js
```

## نسخه

Plugin Header:

`Version: 1.1.0`

Description:

`گزارش فروش سفارش‌ها و محصولات با تاریخ شمسی، فیلتر وضعیت‌ها، خروجی CSV/XLSX واقعی و گزارش تعدادی محصولات.`

## Dependency/Lock status

در آرشیو v1.1.0 فایل‌های `composer.json`، `composer.lock`، `package.json` یا `package-lock.json` وجود ندارند. بنابراین Lock file ساختگی به Repo اضافه نشده است.

برای توسعه بعدی ZIP را Extract کن و سورس را مستقیماً از همین نسخه ادامه بده.
