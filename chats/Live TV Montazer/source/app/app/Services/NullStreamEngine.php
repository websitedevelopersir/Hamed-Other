<?php
namespace TV\Services;
class NullStreamEngine implements StreamEngineInterface {
    public function health(): array{return ['ok'=>false,'configured'=>false,'message'=>'Media Server هنوز متصل نشده است.'];}
    public function publishChannel(int $channelId,array $state): array{return ['ok'=>false,'configured'=>false];}
    public function stopChannel(int $channelId): array{return ['ok'=>false,'configured'=>false];}
    public function inputStatus(int $inputId): array{return ['ok'=>false,'configured'=>false,'status'=>'unknown'];}
}
