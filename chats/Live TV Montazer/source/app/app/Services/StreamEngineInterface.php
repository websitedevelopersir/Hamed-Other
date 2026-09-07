<?php
namespace TV\Services;
interface StreamEngineInterface {
    public function health(): array;
    public function publishChannel(int $channelId,array $state): array;
    public function stopChannel(int $channelId): array;
    public function inputStatus(int $inputId): array;
}
