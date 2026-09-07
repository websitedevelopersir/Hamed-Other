# Decisions

1. نسخه مرجع توسعه فعلی فقط `1.2.6` است.
2. تشخیص نسخه از `VERSION` داخلی انجام شود، نه صرفاً نام فایل یا تیتر README.
3. پروژه standalone PHP است و نباید به معماری WordPress تبدیل شود.
4. پنل مدیریت و Media Server از هم جدا بمانند؛ اتصال از `StreamEngineInterface` انجام می‌شود.
5. تا قبل از اتصال سرور واقعی، `NullStreamEngine` باید مانع وابستگی اجباری پنل به سرویس خارجی باشد.
6. ساعت و Schedule هر شبکه براساس timezone همان شبکه محاسبه شود.
7. Schedule خطی فقط Entry با Duration مثبت دارد؛ Live نامحدود از Quick Play/Override انجام می‌شود.
8. Schedule writes باید Transaction + Row Lock + overlap detection را حفظ کنند.
9. Drag/Reorder باید زمان‌های start/end را دقیق بازسازی کند و Collision بیرونی را رد کند.
10. Secretهای Live و ParsGreen نباید plaintext ذخیره یا دوباره داخل فرم نمایش داده شوند.
11. API Key خام فقط یک بار نمایش داده شود و در DB به‌صورت Hash نگهداری شود.
12. UI مدیریتی Dason و RTL حفظ شود.
13. Player عمومی `watch.php` از UI ادمین مستقل بماند تا تغییر پنل، Playback را خراب نکند.
14. نسخه بعدی نباید امنیت Upload، SSRF، CSRF، Session و Rate Limit را عقب‌گرد دهد.
15. مرحله Server/Playout باید بعداً روی FFmpeg + MediaMTX + HLS/CDN سوار شود، نه با بازنویسی هسته پنل.
