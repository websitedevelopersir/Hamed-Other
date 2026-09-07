# Rebuild full custom/application source — v1.2.6

این پوشه ۹ Part دقیق از آرشیو کامل **۳۷ فایل اختصاصی Internet TV Panel v1.2.6** را نگه می‌دارد.

## مشخصات خروجی نهایی

- Output: `internet-tv-panel-v1.2.6-custom-source.zip`
- Size: `70,112 bytes`
- SHA-256: `2d50e6915c31e24012fc53b32227d4ab80190a6d12280983eb497eed942fc298`
- Custom/application files: `37/37`

## Linux / macOS

از داخل همین پوشه اجرا شود:

```bash
rm -f ../internet-tv-panel-v1.2.6-custom-source.zip
for f in part-00.b64 part-01.b64 part-02.b64 part-03.b64 part-04.b64 part-05.b64 part-06.b64 part-07.b64 part-08.b64; do
  base64 --decode "$f" >> ../internet-tv-panel-v1.2.6-custom-source.zip
done

wc -c ../internet-tv-panel-v1.2.6-custom-source.zip
sha256sum ../internet-tv-panel-v1.2.6-custom-source.zip
unzip -t ../internet-tv-panel-v1.2.6-custom-source.zip
unzip -l ../internet-tv-panel-v1.2.6-custom-source.zip
```

در macOS اگر `base64 --decode` پشتیبانی نشد:

```bash
base64 -D "$f"
```

## Windows PowerShell

```powershell
$out = "..\internet-tv-panel-v1.2.6-custom-source.zip"
if (Test-Path $out) { Remove-Item $out }
$parts = 0..8 | ForEach-Object { "part-{0:D2}.b64" -f $_ }
foreach ($part in $parts) {
    $bytes = [Convert]::FromBase64String((Get-Content $part -Raw).Trim())
    $stream = [System.IO.File]::Open($out, [System.IO.FileMode]::Append)
    $stream.Write($bytes, 0, $bytes.Length)
    $stream.Close()
}
(Get-Item $out).Length
(Get-FileHash $out -Algorithm SHA256).Hash
```

SHA-256 خروجی باید دقیقاً این باشد:

`2d50e6915c31e24012fc53b32227d4ab80190a6d12280983eb497eed942fc298`

## محدوده این Source

این آرشیو تمام **کد و فایل‌های اختصاصی برنامه** را دارد؛ از جمله:

- `index.php`
- `watch.php`
- `ajax.php`
- `api.php`
- `login.php`
- Installer
- Core classes
- Services
- Database schema
- Layouts
- Custom CSS/JS
- JalaliDatePicker CSS
- README / Release manifest / notices

عمداً داخل این Custom Source نیست:

- `public/assets/dason/` — مجموعه حجیم Third-party Dason
- `public/uploads/` — فایل‌های Runtime/User Uploads

نسخه کامل Release اصلی که Dason را هم دارد، فایل دیگری است و مشخصات آن در `../../release/RELEASE_INFO.md` ثبت شده است.
