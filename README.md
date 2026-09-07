# Hamed Other

این Repository آرشیو توسعه پروژه **Hamed Other** است.

هر گفتگوی توسعه در پوشه مستقل زیر `chats/` نگهداری می‌شود تا در توسعه‌های بعدی بتوان وضعیت دقیق همان گفتگو، آخرین کد، تصمیم‌ها، باگ‌ها و موارد باز را بدون اتکا به حافظه بازسازی کرد.

## ساختار

```text
chats/
└── <chat-id>/
    ├── README.md
    ├── context/
    │   ├── CHAT_CONTEXT.md
    │   └── DECISIONS.md
    ├── docs/
    │   ├── DEVELOPMENT_GUIDE.md
    │   └── KNOWN_ISSUES.md
    ├── logs/
    │   └── DEVELOPMENT_LOG.md
    └── source/
        └── latest-known.html
```

## قانون توسعه

1. قبل از هر تغییر، فایل‌های Context و Development Guide همان چت خوانده شوند.
2. `source/` باید آخرین مبنای توسعه همان گفتگو باشد.
3. هر تغییر جدید در `logs/DEVELOPMENT_LOG.md` ثبت شود.
4. اگر نسخه/فایل جدید ساخته شد، آخرین فایل مرجع جایگزین شود و وضعیت قبلی در Git History باقی بماند.
5. رمزها، API Keyها، توکن‌ها، اطلاعات اتصال و Credential واقعی هرگز Commit نشوند.
