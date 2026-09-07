<?php

if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) { return $needle === '' || strpos($haystack, $needle) === 0; }
}
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) { return $needle === '' || substr($haystack, -strlen($needle)) === $needle; }
}
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) { return $needle === '' || strpos($haystack, $needle) !== false; }
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Strict',
        'secure' => $secure,
        'path' => '/',
    ]);
    session_start();
}

define('TV_ROOT', __DIR__);

$script = basename((string)($_SERVER['SCRIPT_NAME'] ?? ''));
if (PHP_SAPI !== 'cli') {
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
    if ($script !== 'watch.php') header('X-Frame-Options: DENY');
}

if (!file_exists(TV_ROOT.'/config.php')) {
    if(!str_contains($_SERVER['REQUEST_URI']??'','/install/')) { header('Location: install/'); exit; }
    return;
}
$GLOBALS['config']=$config=require TV_ROOT.'/config.php';
date_default_timezone_set($config['app']['timezone']??'Asia/Tehran');
spl_autoload_register(function($class){ if(str_starts_with($class,'TV\\')){ $rel=str_replace('TV\\','',$class);$f=TV_ROOT.'/app/'.str_replace('\\','/',$rel).'.php';if(file_exists($f))require $f; }});
require_once TV_ROOT.'/app/Core/Helpers.php';
use TV\Core\Database; use TV\Core\Settings;
$GLOBALS['db']=Database::connect($config);
$GLOBALS['settings']=new Settings($GLOBALS['db']);
