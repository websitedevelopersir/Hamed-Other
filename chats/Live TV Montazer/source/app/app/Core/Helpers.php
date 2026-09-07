<?php
namespace TV\Core;
class Helpers {
    public static function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
    public static function url(string $path=''): string {
        $base = rtrim((string)($GLOBALS['config']['app']['url'] ?? ''), '/');
        $base = preg_replace('/[\x00-\x1F\x7F"\'<>]/u', '', $base) ?: '';
        $path = preg_replace('/[\x00-\x1F\x7F"\'<>]/u', '', $path) ?: '';
        return $base.'/'.ltrim($path,'/');
    }
    public static function redirect(string $to): void { header('Location: '.$to, true, 302); exit; }
    public static function flash(string $type,string $message): void { $_SESSION['flash'][]=['type'=>$type,'message'=>$message]; }
    public static function flashes(): array { $f=$_SESSION['flash']??[]; unset($_SESSION['flash']); return $f; }
    public static function randomToken(int $bytes=32): string { return bin2hex(random_bytes($bytes)); }
    public static function mobile(string $m): string { $m=preg_replace('/\D+/u','',$m); if(str_starts_with($m,'98')&&strlen($m)===12)$m='0'.substr($m,2); return $m; }
    public static function now(): string { return date('Y-m-d H:i:s'); }
    public static function datetimeLocal(?string $dt): string { return $dt?date('Y-m-d\TH:i',strtotime($dt)):''; }
    public static function duration(int $seconds): string { $h=floor($seconds/3600);$m=floor(($seconds%3600)/60);$s=$seconds%60; return sprintf('%02d:%02d:%02d',$h,$m,$s); }
    public static function slug(string $s): string {
        $s = trim(function_exists('mb_strtolower') ? mb_strtolower($s, 'UTF-8') : strtolower($s));
        $s = preg_replace('/[^\p{L}\p{N}]+/u','-',$s);
        return trim((string)$s,'-') ?: 'channel-'.substr(self::randomToken(4),0,6);
    }
    public static function text($value,int $max=190,bool $allowEmpty=true): string {
        $v=trim((string)$value);
        if(!$allowEmpty&&$v==='')throw new \Exception('مقدار الزامی وارد نشده است.');
        if (function_exists('mb_strlen')) {
            $length = mb_strlen($v,'UTF-8');
        } else {
            $ok = preg_match_all('/./us', $v, $chars);
            $length = $ok === false ? strlen($v) : count($chars[0]);
        }
        if($length>$max)throw new \Exception('طول یکی از ورودی‌ها بیشتر از حد مجاز است.');
        return $v;
    }
    public static function excerpt($value,int $max=80,string $suffix='…'): string {
        $v=(string)$value;
        if(function_exists('mb_strimwidth')) return mb_strimwidth($v,0,$max,$suffix,'UTF-8');
        $chars = preg_split('//u', $v, -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($chars)) return strlen($v)>$max?substr($v,0,$max).$suffix:$v;
        return count($chars)>$max?implode('',array_slice($chars,0,$max)).$suffix:$v;
    }
    public static function enum($value,array $allowed,string $default=''): string { $v=(string)$value; return in_array($v,$allowed,true)?$v:$default; }
    public static function color($value,string $default='#0f766e'): string { $v=(string)$value; return preg_match('/^#[0-9a-fA-F]{6}$/',$v)?strtolower($v):$default; }
    public static function timezone($value,string $default='Asia/Tehran'): string { $v=(string)$value; try { new \DateTimeZone($v); return $v; } catch(\Throwable $e){ return $default; } }
    public static function httpUrl($value,bool $allowEmpty=true): string { $v=trim((string)$value); if($v===''&&$allowEmpty)return ''; if(!Security::isPublicHttpUrl($v))throw new \Exception('آدرس اینترنتی معتبر و عمومی نیست.'); return $v; }
}
