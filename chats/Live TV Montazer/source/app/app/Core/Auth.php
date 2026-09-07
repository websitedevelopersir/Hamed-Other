<?php
namespace TV\Core;
class Auth {
    public static function user(): ?array { return $_SESSION['user']??null; }
    public static function check(): bool { return !empty($_SESSION['user']['id']); }
    public static function requireLogin(): void {
        if(!self::check()) Helpers::redirect(Helpers::url('login.php'));
        $now=time();$idle=14400;$absolute=43200;
        if(isset($GLOBALS['settings'])){$idle=max(900,(int)$GLOBALS['settings']->get('security_session_idle','14400'));$absolute=max($idle,(int)$GLOBALS['settings']->get('security_session_absolute','43200'));}
        $last=(int)($_SESSION['_last_activity']??$now);$created=(int)($_SESSION['_session_created']??$now);
        if(($now-$last)>$idle||($now-$created)>$absolute){self::logout();Helpers::redirect(Helpers::url('login.php?expired=1'));}
        $_SESSION['_last_activity']=$now;
    }
    public static function login(array $u): void { session_regenerate_id(true); $_SESSION['user']=['id'=>$u['id'],'name'=>$u['name'],'mobile'=>$u['mobile'],'role'=>$u['role']]; $_SESSION['_session_created']=time();$_SESSION['_last_activity']=time();unset($_SESSION['_csrf']);Csrf::token(); }
    public static function logout(): void { $_SESSION=[]; if(ini_get('session.use_cookies')){ $p=session_get_cookie_params(); setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']); } session_destroy(); }
    public static function can(string $cap): bool {
        $r=self::user()['role']??'';
        if(in_array($r,['super_admin','admin'],true)) return true;
        $map=[
            'operator'=>['dashboard','channels.view','channels.manage','schedule.manage','live.manage','ticker.manage'],
            'content'=>['dashboard','media.manage','schedule.manage','channels.view'],
            'ads'=>['dashboard','ads.manage','ticker.manage','channels.view'],
            'viewer'=>['dashboard','channels.view'],
        ];
        return in_array($cap,$map[$r]??[],true);
    }
    public static function requireCap(string $cap): void { if(!self::can($cap)){ http_response_code(403); exit('دسترسی کافی ندارید.'); } }
}
