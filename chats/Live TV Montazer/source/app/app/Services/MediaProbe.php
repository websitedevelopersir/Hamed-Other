<?php
namespace TV\Services;

class MediaProbe
{
    public static function probe(string $source): array
    {
        $result = [
            'duration' => 0,
            'resolution' => null,
            'ffprobe' => false,
        ];

        $source = trim($source);
        if ($source === '') {
            return $result;
        }

        $isLocal = is_file($source);
        $isRemote = (bool) preg_match('#^https?://#i', $source);
        if (!$isLocal && !$isRemote) {
            return $result;
        }

        $binary = self::ffprobeBinary();
        if ($binary === null) {
            return $result;
        }

        $timeout = $isRemote ? ' -rw_timeout 8000000 ' : ' ';
        $cmd = escapeshellarg($binary)
            . ' -v error' . $timeout
            . '-show_entries format=duration:stream=width,height -of json '
            . escapeshellarg($source)
            . ' 2>/dev/null';

        $raw = @shell_exec($cmd);
        if (!is_string($raw) || trim($raw) === '') {
            return $result;
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return $result;
        }

        $duration = isset($data['format']['duration']) ? (float) $data['format']['duration'] : 0.0;
        if (is_finite($duration) && $duration > 0) {
            $result['duration'] = (int) ceil($duration);
        }

        if (!empty($data['streams']) && is_array($data['streams'])) {
            foreach ($data['streams'] as $stream) {
                $width = isset($stream['width']) ? (int) $stream['width'] : 0;
                $height = isset($stream['height']) ? (int) $stream['height'] : 0;
                if ($width > 0 && $height > 0) {
                    $result['resolution'] = $width . 'x' . $height;
                    break;
                }
            }
        }

        $result['ffprobe'] = true;
        return $result;
    }

    private static function ffprobeBinary(): ?string
    {
        if (!function_exists('shell_exec')) {
            return null;
        }

        $disabled = array_filter(array_map('trim', explode(',', (string) ini_get('disable_functions'))));
        if (in_array('shell_exec', $disabled, true)) {
            return null;
        }

        $binary = trim((string) @shell_exec('command -v ffprobe 2>/dev/null'));
        if ($binary === '' || !is_file($binary)) {
            return null;
        }

        return $binary;
    }
}
