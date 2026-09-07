<?php
require __DIR__.'/bootstrap.php';
use TV\Core\Helpers; use TV\Core\Csrf; use TV\Core\Auth; use TV\Core\Security; use TV\Services\SmsService; use TV\Services\Audit;
if(Auth::check()) Helpers::redirect(Helpers::url('index.php'));
$db=$GLOBALS['db'];$settings=$GLOBALS['settings'];Security::ensureLoginTable($db);
$error='';$notice='';$otpStep=false;$otpMobile='';
if(isset($_GET['expired']))$notice='نشست شما به دلیل عدم فعالیت منقضی شد. دوباره وارد شوید.';
if($_SERVER['REQUEST_METHOD']==='POST'){
  Csrf::verify();$action=(string)($_POST['action']??'password_login');
  if($action==='password_login'){
    if($settings->get('auth_password_enabled','1')!=='1'){$error='ورود با رمز عبور غیرفعال است.';}
    else{
      $mobile=Helpers::mobile($_POST['mobile']??'');$pass=(string)($_POST['password']??'');$rateKey=$mobile!==''?$mobile:'invalid';
      if(!preg_match('/^09\d{9}$/',$mobile)||Security::tooMany($db,$rateKey,'password',5,900)||Security::tooMany($db,'__global__','password_global',30,900,true)){$error='شماره موبایل یا رمز عبور صحیح نیست یا تلاش‌های زیادی انجام شده است.';}
      else{
        $s=$db->prepare('SELECT * FROM users WHERE mobile=? AND status=1 LIMIT 1');$s->execute([$mobile]);$u=$s->fetch();$dummy='$2y$12$mmOe155yFbbC/8VMPTEXXOfgddilLfynblYO/OBqya2X0q0ZOuVUu';$hash=($u&&$u['password_hash'])?(string)$u['password_hash']:$dummy;$verified=password_verify($pass,$hash);$ok=(bool)$u&&$verified;
        Security::record($db,$rateKey,'password',(bool)$ok);Security::record($db,'__global__','password_global',(bool)$ok);
        if($ok){$db->prepare('UPDATE users SET last_login_at=NOW() WHERE id=?')->execute([$u['id']]);Auth::login($u);Audit::log($db,'login.password','user',(int)$u['id'],'ورود با رمز عبور');Helpers::redirect(Helpers::url('index.php'));}
        $error='شماره موبایل یا رمز عبور صحیح نیست.';
      }
    }
  }elseif($action==='send_otp'){
    $otpMobile=Helpers::mobile($_POST['mobile']??'');$otpStep=true;$rateKey=$otpMobile!==''?$otpMobile:'invalid';
    if($settings->get('auth_sms_enabled','0')!=='1'){$error='ورود پیامکی غیرفعال است.';}
    elseif(!preg_match('/^09\d{9}$/',$otpMobile)){$error='شماره موبایل معتبر وارد کنید.';}
    elseif(Security::tooMany($db,$rateKey,'otp_send',3,600,true)||Security::tooMany($db,'__global__','otp_send_global',10,600,true)){$error='تعداد درخواست کد بیش از حد مجاز است. چند دقیقه دیگر دوباره تلاش کنید.';}
    else{
      Security::record($db,$rateKey,'otp_send',true);Security::record($db,'__global__','otp_send_global',true);
      $s=$db->prepare('SELECT * FROM users WHERE mobile=? AND status=1 LIMIT 1');$s->execute([$otpMobile]);$u=$s->fetch();
      if($u){
        $code=(string)random_int(100000,999999);$hash=password_hash($code,PASSWORD_DEFAULT);$db->prepare('UPDATE otp_codes SET consumed_at=NOW() WHERE mobile=? AND consumed_at IS NULL')->execute([$otpMobile]);$db->prepare('INSERT INTO otp_codes(mobile,code_hash,expires_at) VALUES(?,?,DATE_ADD(NOW(),INTERVAL 2 MINUTE))')->execute([$otpMobile,$hash]);
        $res=(new SmsService($settings))->sendOtp($otpMobile,$code);
        if(!$res['ok']){$db->prepare('UPDATE otp_codes SET consumed_at=NOW() WHERE mobile=? AND consumed_at IS NULL')->execute([$otpMobile]);$error='ارسال کد در حال حاضر انجام نشد.';if(($GLOBALS['config']['app']['debug']??false))$notice='کد تست: '.$code;}
      } else { usleep(random_int(100000,220000)); }
      if($error==='')$notice='اگر حساب فعالی با این شماره وجود داشته باشد، کد تایید ارسال می‌شود.';
    }
  }elseif($action==='verify_otp'){
    $otpMobile=Helpers::mobile($_POST['mobile']??'');$otpStep=true;$code=preg_replace('/\D+/','',(string)($_POST['code']??''));$rateKey=$otpMobile!==''?$otpMobile:'invalid';
    if(Security::tooMany($db,$rateKey,'otp_verify',6,600)||Security::tooMany($db,'__global__','otp_verify_global',30,600,true)){$error='تعداد تلاش بیش از حد مجاز است. کمی بعد دوباره تلاش کنید.';}
    else{
      $s=$db->prepare('SELECT * FROM otp_codes WHERE mobile=? AND consumed_at IS NULL AND expires_at>NOW() ORDER BY id DESC LIMIT 1');$s->execute([$otpMobile]);$otp=$s->fetch();
      if(!$otp){Security::record($db,$rateKey,'otp_verify',false);Security::record($db,'__global__','otp_verify_global',false);$error='کد منقضی یا نامعتبر است. مجدداً کد دریافت کنید.';}
      elseif((int)$otp['attempts']>=5){Security::record($db,$rateKey,'otp_verify',false);Security::record($db,'__global__','otp_verify_global',false);$error='تعداد تلاش مجاز تمام شده است.';}
      elseif(strlen($code)!==6||!password_verify($code,$otp['code_hash'])){$db->prepare('UPDATE otp_codes SET attempts=attempts+1 WHERE id=?')->execute([$otp['id']]);Security::record($db,$rateKey,'otp_verify',false);Security::record($db,'__global__','otp_verify_global',false);$error='کد تایید نادرست است.';}
      else{
        $db->prepare('UPDATE otp_codes SET consumed_at=NOW() WHERE id=?')->execute([$otp['id']]);$uStmt=$db->prepare('SELECT * FROM users WHERE mobile=? AND status=1 LIMIT 1');$uStmt->execute([$otpMobile]);$u=$uStmt->fetch();
        if(!$u){Security::record($db,$rateKey,'otp_verify',false);Security::record($db,'__global__','otp_verify_global',false);$error='ورود انجام نشد.';}else{Security::record($db,$rateKey,'otp_verify',true);Security::record($db,'__global__','otp_verify_global',true);$db->prepare('UPDATE users SET last_login_at=NOW() WHERE id=?')->execute([$u['id']]);Auth::login($u);Audit::log($db,'login.otp','user',(int)$u['id'],'ورود پیامکی');Helpers::redirect(Helpers::url('index.php'));}
      }
    }
  }
}
$appName=$settings->get('app_name',$GLOBALS['config']['app']['name']);$passwordEnabled=$settings->get('auth_password_enabled','1')==='1';$smsEnabled=$settings->get('auth_sms_enabled','0')==='1';
$loginLogo=$settings->get('login_logo','public/assets/dason/images/logo-sm.svg');
$loginBg=$settings->get('login_background','public/assets/dason/images/auth-bg.jpg');
$loginSlidesMode=Helpers::enum($settings->get('login_slides_mode','image'),['off','text','image'],'image');
$loginAvatars=[
  $settings->get('login_avatar_1','public/assets/dason/images/users/avatar-1.jpg'),
  $settings->get('login_avatar_2','public/assets/dason/images/users/avatar-2.jpg'),
  $settings->get('login_avatar_3','public/assets/dason/images/users/avatar-3.jpg')
];
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود | <?=Helpers::e($appName)?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?=Helpers::url('public/assets/dason/css/preloader-rtl.min.css')?>">
    <link rel="stylesheet" href="<?=Helpers::url('public/assets/dason/css/bootstrap-rtl.min.css')?>">
    <link rel="stylesheet" href="<?=Helpers::url('public/assets/dason/css/icons-rtl.min.css')?>">
    <link rel="stylesheet" href="<?=Helpers::url('public/assets/dason/css/app-rtl.min.css')?>">
    <link rel="stylesheet" href="<?=Helpers::url('public/assets/css/dason-panel.css')?>">
</head>
<body data-topbar="dark">
<div class="auth-page">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-xxl-3 col-lg-4 col-md-5">
                <div class="auth-full-page-content d-flex p-sm-5 p-4">
                    <div class="w-100">
                        <div class="d-flex flex-column h-100">
                            <div class="mb-4 mb-md-5 text-center auth-logo">
                                <a href="<?=Helpers::url('login.php')?>" class="d-block auth-logo">
                                    <img src="<?=Helpers::url($loginLogo)?>" alt="" height="28">
                                    <span class="logo-txt"><?=Helpers::e($appName)?></span>
                                </a>
                            </div>
                            <div class="auth-content my-auto">
                                <div class="text-center">
                                    <h5 class="mb-0">خوش آمدید</h5>
                                    <p class="text-muted mt-2">برای ادامه وارد پنل مدیریت شوید.</p>
                                </div>
                                <?php if($error):?><div class="alert alert-danger mt-4 mb-0"><?=Helpers::e($error)?></div><?php endif;?>
                                <?php if($notice):?><div class="alert alert-success mt-4 mb-0"><?=Helpers::e($notice)?></div><?php endif;?>

                                <?php if($passwordEnabled&&$smsEnabled):?>
                                <ul class="nav nav-pills nav-justified mt-4" role="tablist">
                                    <li class="nav-item"><button type="button" class="nav-link <?=$otpStep?'':'active'?>" data-login-tab="passwordPane">رمز عبور</button></li>
                                    <li class="nav-item"><button type="button" class="nav-link <?=$otpStep?'active':''?>" data-login-tab="otpPane">ورود پیامکی</button></li>
                                </ul>
                                <?php endif;?>

                                <?php if($passwordEnabled):?>
                                <div class="login-pane <?=$otpStep&&$smsEnabled?'d-none':'active'?>" id="passwordPane">
                                    <form class="mt-4 pt-2" method="post">
                                        <input type="hidden" name="action" value="password_login"><?=Csrf::input()?>
                                        <div class="form-floating form-floating-custom mb-4">
                                            <input type="text" class="form-control" id="input-mobile" name="mobile" inputmode="numeric" autocomplete="username" placeholder="0912xxxxxxx" required>
                                            <label for="input-mobile">شماره موبایل</label>
                                            <div class="form-floating-icon"><i data-feather="smartphone"></i></div>
                                        </div>
                                        <div class="form-floating form-floating-custom mb-4 auth-pass-inputgroup">
                                            <input type="password" class="form-control pe-5" id="password-input" name="password" autocomplete="current-password" placeholder="رمز عبور" required>
                                            <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon"><i class="mdi mdi-eye-outline font-size-18 text-muted"></i></button>
                                            <label for="password-input">رمز عبور</label>
                                            <div class="form-floating-icon"><i data-feather="lock"></i></div>
                                        </div>
                                        <div class="mb-3"><button class="btn btn-primary w-100 waves-effect waves-light" type="submit">ورود به پنل</button></div>
                                    </form>
                                </div>
                                <?php endif;?>

                                <?php if($smsEnabled):?>
                                <div class="login-pane <?=$otpStep?'active':'d-none'?>" id="otpPane">
                                    <?php if(!$otpStep):?>
                                    <form class="mt-4 pt-2" method="post">
                                        <input type="hidden" name="action" value="send_otp"><?=Csrf::input()?>
                                        <div class="form-floating form-floating-custom mb-4">
                                            <input type="text" class="form-control" id="otp-mobile" name="mobile" inputmode="numeric" placeholder="0912xxxxxxx" required>
                                            <label for="otp-mobile">شماره موبایل</label>
                                            <div class="form-floating-icon"><i data-feather="smartphone"></i></div>
                                        </div>
                                        <div class="mb-3"><button class="btn btn-primary w-100 waves-effect waves-light" type="submit">ارسال کد تایید</button></div>
                                    </form>
                                    <?php else:?>
                                    <form class="mt-4 pt-2" method="post">
                                        <input type="hidden" name="action" value="verify_otp"><input type="hidden" name="mobile" value="<?=Helpers::e($otpMobile)?>"><?=Csrf::input()?>
                                        <div class="form-floating form-floating-custom mb-4">
                                            <input type="text" class="form-control text-center" id="otp-code" name="code" inputmode="numeric" maxlength="6" autocomplete="one-time-code" placeholder="کد ۶ رقمی" required>
                                            <label for="otp-code">کد ۶ رقمی</label>
                                            <div class="form-floating-icon"><i data-feather="shield"></i></div>
                                        </div>
                                        <div class="mb-3"><button class="btn btn-primary w-100 waves-effect waves-light" type="submit">تایید و ورود</button></div>
                                    </form>
                                    <form method="post"><input type="hidden" name="action" value="send_otp"><input type="hidden" name="mobile" value="<?=Helpers::e($otpMobile)?>"><?=Csrf::input()?><button class="btn btn-soft-primary w-100" type="submit">ارسال مجدد کد</button></form>
                                    <?php endif;?>
                                </div>
                                <?php endif;?>
                            </div>
                            <div class="mt-4 mt-md-5 text-center"><p class="mb-0">© <script>document.write(new Date().getFullYear())</script> <?=Helpers::e($appName)?></p></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-9 col-lg-8 col-md-7">
                <div class="auth-bg pt-md-5 p-4 d-flex" style="background-image:url('<?=Helpers::e(Helpers::url($loginBg))?>')">
                    <div class="bg-overlay"></div>
                    <ul class="bg-bubbles"><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li></ul>
                    <div class="row justify-content-center align-items-end w-100">
                        <div class="col-xl-7">
                            <?php if($loginSlidesMode!=='off'):?>
                            <div class="p-0 p-sm-4 px-xl-0">
                                <div id="reviewcarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                    <?php if($loginSlidesMode==='image'):?>
                                    <div class="carousel-indicators auth-carousel carousel-indicators-rounded justify-content-center mb-0">
                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="اسلاید ۱"><img src="<?=Helpers::url($loginAvatars[0])?>" class="avatar-md img-fluid rounded-circle d-block" alt=""></button>
                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="اسلاید ۲"><img src="<?=Helpers::url($loginAvatars[1])?>" class="avatar-md img-fluid rounded-circle d-block" alt=""></button>
                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="اسلاید ۳"><img src="<?=Helpers::url($loginAvatars[2])?>" class="avatar-md img-fluid rounded-circle d-block" alt=""></button>
                                    </div>
                                    <?php else:?>
                                    <div class="carousel-indicators login-text-indicators justify-content-center mb-0">
                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="اسلاید ۱"></button>
                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="اسلاید ۲"></button>
                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="اسلاید ۳"></button>
                                    </div>
                                    <?php endif;?>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active"><div class="testi-contain text-center text-white"><i class="bx bxs-quote-alt-left text-success display-6"></i><h4 class="mt-4 fw-medium lh-base text-white">مدیریت یکپارچه شبکه‌های تلویزیون اینترنتی و پخش زنده.</h4></div></div>
                                        <div class="carousel-item"><div class="testi-contain text-center text-white"><i class="bx bxs-quote-alt-left text-success display-6"></i><h4 class="mt-4 fw-medium lh-base text-white">جدول پخش، رسانه‌ها و ورودی‌های زنده در یک محیط مدیریتی.</h4></div></div>
                                        <div class="carousel-item"><div class="testi-contain text-center text-white"><i class="bx bxs-quote-alt-left text-success display-6"></i><h4 class="mt-4 fw-medium lh-base text-white">کنترل تبلیغات، متن متحرک و تنظیمات پلیر برای هر شبکه.</h4></div></div>
                                    </div>
                                </div>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?=Helpers::url('public/assets/dason/libs/jquery/jquery.min.js')?>"></script>
<script src="<?=Helpers::url('public/assets/dason/libs/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?=Helpers::url('public/assets/dason/libs/metismenu/metisMenu.min.js')?>"></script>
<script src="<?=Helpers::url('public/assets/dason/libs/simplebar/simplebar.min.js')?>"></script>
<script src="<?=Helpers::url('public/assets/dason/libs/node-waves/waves.min.js')?>"></script>
<script src="<?=Helpers::url('public/assets/dason/libs/feather-icons/feather.min.js')?>"></script>
<script src="<?=Helpers::url('public/assets/dason/libs/pace-js/pace.min.js')?>"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
<script src="<?=Helpers::url('public/assets/dason/js/app.js')?>"></script>
<script src="<?=Helpers::url('public/assets/js/dason-panel.js')?>"></script>
</body></html>
