<?php
namespace TV\Core;

use PDO;

class Security
{
    public static function headers(bool $allowFraming = false): void
    {
        if (headers_sent()) return;
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
        if (!$allowFraming) {
            header('X-Frame-Options: DENY');
        }
    }

    public static function ensureLoginTable(PDO $db): void
    {
        $db->exec("CREATE TABLE IF NOT EXISTS login_attempts (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            identifier_hash CHAR(64) NOT NULL,
            ip_address VARCHAR(64) NOT NULL,
            action VARCHAR(40) NOT NULL,
            success TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_login_attempts_lookup(identifier_hash, ip_address, action, created_at),
            INDEX idx_login_attempts_created(created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public static function ip(): string
    {
        $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }

    public static function identifier(string $value): string
    {
        $normalized = trim($value);
        if (function_exists('mb_strtolower')) {
            $normalized = mb_strtolower($normalized, 'UTF-8');
        } else {
            $normalized = strtolower($normalized);
        }
        return hash('sha256', $normalized);
    }

    public static function tooMany(PDO $db, string $identifier, string $action, int $limit, int $windowSeconds, bool $countAll = false): bool
    {
        self::ensureLoginTable($db);
        $since = date('Y-m-d H:i:s', time() - max(60, $windowSeconds));
        $sql = 'SELECT COUNT(*) FROM login_attempts WHERE identifier_hash=? AND ip_address=? AND action=?'.($countAll ? '' : ' AND success=0').' AND created_at>=?';
        $s = $db->prepare($sql);
        $s->execute([self::identifier($identifier), self::ip(), $action, $since]);
        return (int)$s->fetchColumn() >= $limit;
    }

    public static function record(PDO $db, string $identifier, string $action, bool $success): void
    {
        self::ensureLoginTable($db);
        $s = $db->prepare('INSERT INTO login_attempts(identifier_hash,ip_address,action,success) VALUES(?,?,?,?)');
        $s->execute([self::identifier($identifier), self::ip(), $action, $success ? 1 : 0]);
        if (random_int(1, 40) === 1) {
            $db->exec("DELETE FROM login_attempts WHERE created_at < DATE_SUB(NOW(), INTERVAL 14 DAY)");
        }
    }

    public static function uploadDirectory(string $subdir): string
    {
        if (!preg_match('/^[A-Za-z0-9_-]+$/', $subdir)) {
            throw new \Exception('مسیر آپلود معتبر نیست.');
        }
        $root = (defined('TV_ROOT') ? TV_ROOT : dirname(__DIR__, 2)) . '/public/uploads';
        if (!is_dir($root) && !@mkdir($root, 0755, true) && !is_dir($root)) {
            throw new \Exception('پوشه اصلی آپلود ساخته نشد. دسترسی نوشتن پوشه public را بررسی کنید.');
        }
        if (!is_writable($root)) {
            throw new \Exception('پوشه public/uploads قابل نوشتن نیست. Permission هاست را بررسی کنید.');
        }
        $dir = $root . '/' . $subdir;
        if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \Exception('پوشه مقصد آپلود ساخته نشد: ' . $subdir);
        }
        if (!is_writable($dir)) {
            throw new \Exception('پوشه مقصد آپلود قابل نوشتن نیست: ' . $subdir);
        }
        return $dir;
    }

    public static function validateUpload(array $file, array $allowedMime, int $maxBytes): array
    {
        if (!isset($file['error']) || is_array($file['error'])) throw new \Exception('فایل آپلودی نامعتبر است.');
        if ($file['error'] !== UPLOAD_ERR_OK) throw new \Exception('آپلود فایل کامل انجام نشد.');
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) throw new \Exception('فایل آپلودی معتبر نیست.');
        $size = (int)($file['size'] ?? 0);
        if ($size <= 0 || $size > $maxBytes) throw new \Exception('حجم فایل خارج از محدوده مجاز است.');
        if (class_exists('\\finfo')) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = (string)$finfo->file($file['tmp_name']);
        } elseif (function_exists('mime_content_type')) {
            $mime = (string)@mime_content_type($file['tmp_name']);
        } else {
            throw new \Exception('افزونه Fileinfo برای بررسی امن فایل‌ها روی سرور فعال نیست.');
        }
        if (!in_array($mime, $allowedMime, true)) throw new \Exception('نوع واقعی فایل مجاز نیست.');
        return ['mime' => $mime, 'size' => $size];
    }

    public static function isPublicHttpUrl(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) return false;
        $parts = parse_url($url);
        $scheme = strtolower((string)($parts['scheme'] ?? ''));
        if (!in_array($scheme, ['http', 'https'], true)) return false;
        $host = strtolower((string)($parts['host'] ?? ''));
        if ($host === '' || $host === 'localhost' || str_ends_with($host, '.localhost')) return false;
        if (filter_var($host, FILTER_VALIDATE_IP)) return self::isPublicIp($host);
        $ips = [];
        if (function_exists('dns_get_record')) {
            $records = @dns_get_record($host, DNS_A | DNS_AAAA);
            if (is_array($records)) {
                foreach ($records as $record) {
                    $ip = $record['ip'] ?? ($record['ipv6'] ?? null);
                    if (is_string($ip) && $ip !== '') $ips[] = $ip;
                }
            }
        } elseif (function_exists('gethostbynamel')) {
            $resolved = @gethostbynamel($host);
            if (is_array($resolved)) $ips = $resolved;
        }
        if (!$ips) return false;
        foreach (array_unique($ips) as $ip) {
            if (!self::isPublicIp($ip)) return false;
        }
        return true;
    }

    private static function isPublicIp(string $ip): bool
    {
        return (bool)filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
}
