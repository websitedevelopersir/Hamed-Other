# Hamed Other

این Repository آرشیو توسعه چت‌های پروژه **Hamed Other** است.

هر گفتگوی توسعه با **نام واقعی چت** در پوشه مستقل زیر `chats/` نگهداری می‌شود. شناسه و لینک اصلی گفتگو داخل README همان پوشه ثبت می‌شود، اما نام پوشه از Chat ID ساخته نمی‌شود.

## ساختار هر چت

```text
chats/
└── <نام واقعی چت>/
    ├── README.md
    ├── VERSION
    ├── CHANGELOG.md
    ├── context/
    │   ├── CHAT_CONTEXT.md
    │   └── DECISIONS.md
    ├── docs/
    │   ├── DEVELOPMENT_GUIDE.md
    │   └── KNOWN_ISSUES.md
    ├── logs/
    │   └── DEVELOPMENT_LOG.md
    ├── source/
    │   └── SOURCE_INDEX.md
    └── release/
        └── <آخرین فایل واقعی ZIP>
```

## قانون توسعه

1. قبل از هر تغییر، Context، Decisions و Development Guide همان چت خوانده شود.
2. فایل موجود در `release/` آخرین Artifact واقعی و مرجع همان چت است.
3. اگر سورس داخل ZIP باشد، `source/SOURCE_INDEX.md` ساختار و فایل مبنا را مشخص می‌کند.
4. هر تغییر در `CHANGELOG.md` و `logs/DEVELOPMENT_LOG.md` ثبت شود.
5. نسخه‌های قدیمی در Git History باقی می‌مانند و آخرین نسخه جایگزین مبنای جاری می‌شود.
6. رمز، API Key، Token، Credential و تنظیمات حساس واقعی هرگز Commit نشوند.
