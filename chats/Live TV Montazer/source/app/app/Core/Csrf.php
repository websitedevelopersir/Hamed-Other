<?php
namespace TV\Core;
class Csrf {
    public static function token(): string { if(empty($_SESSION['_csrf'])) $_SESSION['_csrf']=bin2hex(random_bytes(32)); return $_SESSION['_csrf']; }
    public static function input(): string { return '<input type="hidden" name="_csrf" value="'.Helpers::e(self::token()).'">'; }
    public static function verify(): void { $v=(string)($_POST['_csrf']??''); if(!$v||!hash_equals((string)($_SESSION['_csrf']??''), $v)){ http_response_code(419); exit('درخواست نامعتبر است. صفحه را تازه‌سازی کنید.'); } }
    public static function rotate(): void { unset($_SESSION['_csrf']); self::token(); }
}
