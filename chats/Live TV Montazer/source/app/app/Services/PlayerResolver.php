<?php
namespace TV\Services;

use DateTimeImmutable;
use DateTimeZone;
use PDO;

class PlayerResolver
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function channel(string $slug): ?array
    {
        $s = $this->db->prepare("SELECT * FROM channels WHERE slug=? AND public_enabled=1 AND status='active' LIMIT 1");
        $s->execute([$slug]);
        $row = $s->fetch();
        return $row ?: null;
    }

    public function firstPublicChannel(): ?array
    {
        $row = $this->db->query("SELECT * FROM channels WHERE public_enabled=1 AND status='active' ORDER BY id LIMIT 1")->fetch();
        return $row ?: null;
    }

    public function nowForChannel(array $channel, ?int $unix = null): array
    {
        $timezone = $channel['timezone'] ?: 'Asia/Tehran';
        try {
            $tz = new DateTimeZone($timezone);
        } catch (\Throwable $e) {
            $tz = new DateTimeZone('Asia/Tehran');
            $timezone = 'Asia/Tehran';
        }

        $now = new DateTimeImmutable('@' . ($unix ?: time()));
        $now = $now->setTimezone($tz);

        return [
            'unix' => $now->getTimestamp(),
            'mysql' => $now->format('Y-m-d H:i:s'),
            'date' => $now->format('Y-m-d'),
            'time' => $now->format('H:i:s'),
            'timezone' => $timezone,
            'iso' => $now->format(DATE_ATOM),
        ];
    }

    public function current(int $channelId, ?int $unix = null): ?array
    {
        $channel = $this->channelById($channelId);
        if (!$channel) {
            return null;
        }
        $clock = $this->nowForChannel($channel, $unix);

        $override = $this->activeOverride($channelId);
        if ($override) {
            return $override;
        }

        // Video advertisement campaigns are a real playout source, not only admin records.
        $ad = $this->activeAdvertisement($channel, $clock);
        if ($ad) {
            return $ad;
        }

        $s = $this->db->prepare(
            "SELECT * FROM schedules
             WHERE channel_id=? AND status!='disabled'
               AND starts_at<=?
               AND (ends_at IS NULL OR ends_at>?)
             ORDER BY starts_at DESC, position DESC, id DESC LIMIT 1"
        );
        $s->execute([$channelId, $clock['mysql'], $clock['mysql']]);
        $schedule = $s->fetch();

        if (!$schedule) {
            return $this->fallback($channel);
        }

        $source = $this->resolveScheduleSource($schedule);
        if (!$source) {
            return $this->fallback($channel);
        }

        return [
            'schedule' => $schedule,
            'kind' => $schedule['source_kind'],
            'source' => $source,
            'title' => $schedule['title'],
            'override' => false,
            'is_ad' => $schedule['source_kind'] === 'ad_break',
            'playout_starts_at' => $schedule['starts_at'],
        ];
    }

    public function next(int $channelId, ?int $unix = null): ?array
    {
        $channel = $this->channelById($channelId);
        if (!$channel) {
            return null;
        }
        $clock = $this->nowForChannel($channel, $unix);
        $s = $this->db->prepare(
            "SELECT id,title,starts_at,ends_at,duration_seconds,source_kind
             FROM schedules
             WHERE channel_id=? AND starts_at>? AND status!='disabled'
             ORDER BY starts_at ASC, position ASC, id ASC LIMIT 1"
        );
        $s->execute([$channelId, $clock['mysql']]);
        $row = $s->fetch();
        return $row ?: null;
    }

    public function tickers(int $channelId, ?int $unix = null): array
    {
        $channel = $this->channelById($channelId);
        if (!$channel) {
            return [];
        }
        $clock = $this->nowForChannel($channel, $unix);
        $s = $this->db->prepare(
            "SELECT * FROM tickers
             WHERE status='active'
               AND (channel_id IS NULL OR channel_id=?)
               AND (starts_at IS NULL OR starts_at<=?)
               AND (ends_at IS NULL OR ends_at>?)
             ORDER BY priority DESC,id DESC"
        );
        $s->execute([$channelId, $clock['mysql'], $clock['mysql']]);
        return $s->fetchAll();
    }

    public function media(int $id): ?array
    {
        $s = $this->db->prepare("SELECT * FROM media WHERE id=? AND status='ready' LIMIT 1");
        $s->execute([$id]);
        $row = $s->fetch();
        return $row ?: null;
    }

    private function channelById(int $id): ?array
    {
        $s = $this->db->prepare("SELECT * FROM channels WHERE id=? AND public_enabled=1 AND status='active' LIMIT 1");
        $s->execute([$id]);
        $row = $s->fetch();
        return $row ?: null;
    }

    private function activeOverride(int $channelId): ?array
    {
        $o = $this->db->prepare("SELECT * FROM channel_overrides WHERE channel_id=? AND enabled=1 LIMIT 1");
        $o->execute([$channelId]);
        $override = $o->fetch();
        if (!$override) {
            return null;
        }

        $source = null;
        if ($override['source_kind'] === 'media') {
            $source = $this->media((int) $override['source_id']);
        } else {
            $q = $this->db->prepare("SELECT * FROM live_inputs WHERE id=? AND status!='disabled' LIMIT 1");
            $q->execute([(int) $override['source_id']]);
            $source = $q->fetch() ?: null;
        }

        if (!$source) {
            return null;
        }

        return [
            'schedule' => [
                'id' => 'override-' . $override['id'],
                'starts_at' => $override['started_at'],
                'ends_at' => null,
                'duration_seconds' => 0,
            ],
            'kind' => $override['source_kind'],
            'source' => $source,
            'title' => $override['title'],
            'override' => true,
            'is_ad' => false,
            'playout_starts_at' => $override['started_at'],
        ];
    }

    private function fallback(array $channel): ?array
    {
        $mediaId = (int) ($channel['fallback_media_id'] ?? 0);
        if (!$mediaId) {
            return null;
        }
        $media = $this->media($mediaId);
        if (!$media) {
            return null;
        }

        return [
            'schedule' => null,
            'kind' => 'media',
            'source' => $media,
            'title' => 'پخش جایگزین',
            'override' => false,
            'is_ad' => false,
            'playout_starts_at' => null,
        ];
    }

    private function resolveScheduleSource(array $schedule): ?array
    {
        if (in_array($schedule['source_kind'], ['media', 'ad_break', 'prayer', 'fallback'], true)) {
            return $schedule['source_id'] ? $this->media((int) $schedule['source_id']) : null;
        }

        if ($schedule['source_kind'] === 'live') {
            $q = $this->db->prepare("SELECT * FROM live_inputs WHERE id=? AND status!='disabled' LIMIT 1");
            $q->execute([(int) $schedule['source_id']]);
            return $q->fetch() ?: null;
        }

        return null;
    }

    private function activeAdvertisement(array $channel, array $clock): ?array
    {
        $s = $this->db->prepare(
            "SELECT a.*,m.title AS media_title,m.duration_seconds AS media_duration
             FROM ads a
             JOIN media m ON m.id=a.media_id AND m.status='ready' AND m.type='video'
             WHERE a.status='active'
               AND (a.channel_id IS NULL OR a.channel_id=?)
               AND (a.starts_on IS NULL OR a.starts_on<=?)
               AND (a.ends_on IS NULL OR a.ends_on>=?)
             ORDER BY a.id ASC"
        );
        $s->execute([(int) $channel['id'], $clock['date'], $clock['date']]);
        $ads = $s->fetchAll();
        if (!$ads) {
            return null;
        }

        foreach ($ads as $ad) {
            $window = $this->advertisementWindow($ad, (int) $channel['id'], $clock);
            if (!$window) {
                continue;
            }
            $media = $this->media((int) $ad['media_id']);
            if (!$media) {
                continue;
            }

            return [
                'schedule' => [
                    'id' => 'ad-' . $ad['id'] . '-' . str_replace('-', '', $clock['date']),
                    'starts_at' => $window['starts_at'],
                    'ends_at' => $window['ends_at'],
                    'duration_seconds' => $window['duration_seconds'],
                ],
                'kind' => 'ad_break',
                'source' => $media,
                'title' => 'تبلیغات · ' . $ad['name'],
                'override' => false,
                'is_ad' => true,
                'ad_id' => (int) $ad['id'],
                'placement' => $ad['placement'],
                'playout_starts_at' => $window['starts_at'],
            ];
        }

        return null;
    }

    private function advertisementWindow(array $ad, int $channelId, array $clock): ?array
    {
        $duration = max(1, (int) ($ad['media_duration'] ?? 0));
        $placement = $ad['placement'] ?: 'scheduled';
        $startUnix = null;

        if ($placement === 'scheduled') {
            if (empty($ad['daily_start'])) {
                return null;
            }
            $startUnix = $this->localDateTimeToUnix($clock['date'] . ' ' . $ad['daily_start'], $clock['timezone']);
        } elseif ($placement === 'pre') {
            $next = $this->scheduleAround($channelId, $clock['mysql'], 'next');
            if (!$next) {
                return null;
            }
            $programStart = $this->localDateTimeToUnix($next['starts_at'], $clock['timezone']);
            $startUnix = $programStart - $duration;
        } elseif ($placement === 'mid') {
            $current = $this->scheduleAround($channelId, $clock['mysql'], 'current');
            if (!$current) {
                return null;
            }
            $programStart = $this->localDateTimeToUnix($current['starts_at'], $clock['timezone']);
            $programDuration = (int) ($current['duration_seconds'] ?? 0);
            if ($programDuration <= 0 && !empty($current['ends_at'])) {
                $programDuration = max(0, $this->localDateTimeToUnix($current['ends_at'], $clock['timezone']) - $programStart);
            }
            if ($programDuration <= $duration + 2) {
                return null;
            }
            $startUnix = $programStart + (int) floor(($programDuration - $duration) / 2);
        } elseif ($placement === 'post') {
            $previous = $this->scheduleAround($channelId, $clock['mysql'], 'previous');
            if (!$previous) {
                return null;
            }
            if (!empty($previous['ends_at'])) {
                $startUnix = $this->localDateTimeToUnix($previous['ends_at'], $clock['timezone']);
            } else {
                $programStart = $this->localDateTimeToUnix($previous['starts_at'], $clock['timezone']);
                $startUnix = $programStart + (int) ($previous['duration_seconds'] ?? 0);
            }
        }

        if (!$startUnix) {
            return null;
        }

        $endUnix = $startUnix + $duration;
        $now = (int) $clock['unix'];
        if ($now < $startUnix || $now >= $endUnix) {
            return null;
        }

        // daily_end is an eligibility boundary, not a command to loop one ad for the whole window.
        if (!empty($ad['daily_end'])) {
            $dailyEnd = $this->localDateTimeToUnix($clock['date'] . ' ' . $ad['daily_end'], $clock['timezone']);
            if ($startUnix >= $dailyEnd) {
                return null;
            }
        }

        return [
            'starts_at' => $this->unixToLocalMysql($startUnix, $clock['timezone']),
            'ends_at' => $this->unixToLocalMysql($endUnix, $clock['timezone']),
            'duration_seconds' => $duration,
        ];
    }

    private function scheduleAround(int $channelId, string $nowMysql, string $mode): ?array
    {
        if ($mode === 'next') {
            $sql = "SELECT * FROM schedules WHERE channel_id=? AND status!='disabled' AND starts_at>? ORDER BY starts_at ASC,id ASC LIMIT 1";
        } elseif ($mode === 'previous') {
            $sql = "SELECT * FROM schedules WHERE channel_id=? AND status!='disabled' AND starts_at<=? ORDER BY starts_at DESC,id DESC LIMIT 1";
        } else {
            $sql = "SELECT * FROM schedules WHERE channel_id=? AND status!='disabled' AND starts_at<=? AND (ends_at IS NULL OR ends_at>?) ORDER BY starts_at DESC,id DESC LIMIT 1";
        }
        $s = $this->db->prepare($sql);
        $mode === 'current' ? $s->execute([$channelId, $nowMysql, $nowMysql]) : $s->execute([$channelId, $nowMysql]);
        $row = $s->fetch();
        return $row ?: null;
    }

    private function localDateTimeToUnix(string $value, string $timezone): int
    {
        try {
            $date = new DateTimeImmutable($value, new DateTimeZone($timezone));
        } catch (\Throwable $e) {
            return 0;
        }
        return $date->getTimestamp();
    }

    private function unixToLocalMysql(int $unix, string $timezone): string
    {
        return (new DateTimeImmutable('@' . $unix))->setTimezone(new DateTimeZone($timezone))->format('Y-m-d H:i:s');
    }
}
