<?php
namespace TV\Services;
use TV\Core\Helpers;
use TV\Core\Settings;
use TV\Core\Crypto;
use TV\Core\Security;
class SmsService {
    private $settings;
    public function __construct(Settings $settings){$this->settings=$settings;}
    public function enabled(): bool { return $this->settings->get('auth_sms_enabled','0')==='1'; }
    public function sendOtp(string $mobile,string $code): array {
        if(!$this->enabled()) return ['ok'=>false,'message'=>'ورود پیامکی غیرفعال است.'];
        $mode=$this->settings->get('parsgreen_mode','soap');
        if($mode==='rest') return $this->sendRest($mobile,$code);
        return $this->sendSoap($mobile,$code);
    }
    private function sendSoap(string $mobile,string $code): array {
        $sig=trim(Crypto::decrypt((string)$this->settings->get('parsgreen_signature','')));
        $from=trim((string)$this->settings->get('parsgreen_sender',''));
        $tpl=(string)$this->settings->get('otp_message','کد ورود شما: {code}');
        if(!$sig||!$from) return ['ok'=>false,'message'=>'تنظیمات پارس‌گرین کامل نیست.'];
        if(!class_exists('SoapClient')) return ['ok'=>false,'message'=>'افزونه SOAP روی PHP فعال نیست.'];
        $text=str_replace('{code}',$code,$tpl);
        $wsdl=trim((string)$this->settings->get('parsgreen_wsdl','http://login.parsgreen.com/Api/SendSMS.asmx?WSDL'));
        if(!Security::isPublicHttpUrl($wsdl)) return ['ok'=>false,'message'=>'آدرس WSDL پارس‌گرین امن یا قابل Resolve نیست.'];
        try{
            @ini_set('soap.wsdl_cache_enabled','0');
            $client=new \SoapClient($wsdl,['connection_timeout'=>10,'exceptions'=>true]);
            $params=['signature'=>$sig,'from'=>$from,'to'=>[$mobile],'text'=>$text,'isFlash'=>false,'udh'=>'','success'=>0,'retStr'=>[0]];
            $resp=(array)$client->SendGroupSMS($params);
            $result=$resp['SendGroupSMSResult']??0;
            return ['ok'=>(int)$result===1,'message'=>(int)$result===1?'پیامک ارسال شد.':'پارس‌گرین ارسال را تأیید نکرد.','raw'=>$result];
        }catch(\Throwable $e){ return ['ok'=>false,'message'=>'خطای پارس‌گرین: '.$e->getMessage()]; }
    }
    private function sendRest(string $mobile,string $code): array {
        $endpoint=trim((string)$this->settings->get('parsgreen_rest_endpoint',''));
        $apiKey=trim(Crypto::decrypt((string)$this->settings->get('parsgreen_api_key','')));
        if(!$endpoint||!$apiKey) return ['ok'=>false,'message'=>'Endpoint یا API Key پارس‌گرین وارد نشده است.'];
        if(!function_exists('curl_init')) return ['ok'=>false,'message'=>'افزونه cURL روی PHP فعال نیست.'];
        if(!Security::isPublicHttpUrl($endpoint)) return ['ok'=>false,'message'=>'Endpoint پارس‌گرین امن یا قابل Resolve نیست.'];
        $payload=['mobile'=>$mobile,'code'=>$code,'api_key'=>$apiKey,'template'=>$this->settings->get('parsgreen_pattern','')];
        $ch=curl_init($endpoint); curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Content-Type: application/json','Accept: application/json'],CURLOPT_POSTFIELDS=>json_encode($payload,JSON_UNESCAPED_UNICODE),CURLOPT_TIMEOUT=>12]);
        $body=curl_exec($ch);$err=curl_error($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
        if($err) return ['ok'=>false,'message'=>'خطای ارتباط با پارس‌گرین: '.$err];
        $ok=$status>=200&&$status<300; return ['ok'=>$ok,'message'=>$ok?'پیامک ارسال شد.':'پارس‌گرین پاسخ ناموفق داد.','raw'=>$body];
    }
}
