<?php
namespace TV\Core;
class Crypto {
    private static function key(): string { return hash('sha256',(string)($GLOBALS['config']['app']['key']??'tv-panel'),true); }
    public static function encrypt(?string $plain): ?string {
        if($plain===null||$plain==='') return null;$iv=random_bytes(12);$tag='';$cipher=openssl_encrypt($plain,'aes-256-gcm',self::key(),OPENSSL_RAW_DATA,$iv,$tag);if($cipher===false)return null;return base64_encode($iv.$tag.$cipher);
    }
    public static function decrypt(?string $encoded): string {
        if(!$encoded)return ''; $raw=base64_decode($encoded,true);if($raw===false||strlen($raw)<29)return $encoded;$iv=substr($raw,0,12);$tag=substr($raw,12,16);$cipher=substr($raw,28);$plain=openssl_decrypt($cipher,'aes-256-gcm',self::key(),OPENSSL_RAW_DATA,$iv,$tag);return $plain===false?'':$plain;
    }
}
