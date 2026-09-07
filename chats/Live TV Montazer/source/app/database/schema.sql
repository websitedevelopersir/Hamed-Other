CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  mobile VARCHAR(20) NOT NULL UNIQUE,
  email VARCHAR(190) NULL UNIQUE,
  password_hash VARCHAR(255) NULL,
  role ENUM('super_admin','admin','operator','content','ads','viewer') NOT NULL DEFAULT 'operator',
  status TINYINT(1) NOT NULL DEFAULT 1,
  last_login_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS settings (
  `key` VARCHAR(190) PRIMARY KEY,
  `value` LONGTEXT NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS otp_codes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  mobile VARCHAR(20) NOT NULL,
  code_hash VARCHAR(255) NOT NULL,
  attempts INT NOT NULL DEFAULT 0,
  expires_at DATETIME NOT NULL,
  consumed_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_otp_mobile (mobile), INDEX idx_otp_exp (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS login_attempts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  identifier_hash CHAR(64) NOT NULL,
  ip_address VARCHAR(64) NOT NULL,
  action VARCHAR(40) NOT NULL,
  success TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_login_attempts_lookup(identifier_hash,ip_address,action,created_at),
  INDEX idx_login_attempts_created(created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS channels (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE,
  description TEXT NULL,
  logo VARCHAR(255) NULL,
  accent_color VARCHAR(20) NOT NULL DEFAULT '#0f766e',
  fallback_media_id BIGINT UNSIGNED NULL,
  timezone VARCHAR(80) NOT NULL DEFAULT 'Asia/Tehran',
  status ENUM('active','paused','maintenance') NOT NULL DEFAULT 'active',
  public_enabled TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS media (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(190) NOT NULL,
  type ENUM('video','audio','image') NOT NULL DEFAULT 'video',
  source_type ENUM('upload','url') NOT NULL DEFAULT 'upload',
  source_url TEXT NOT NULL,
  poster_url TEXT NULL,
  category VARCHAR(100) NULL,
  duration_seconds INT UNSIGNED NOT NULL DEFAULT 0,
  mime_type VARCHAR(120) NULL,
  file_size BIGINT UNSIGNED NULL,
  resolution VARCHAR(40) NULL,
  tags TEXT NULL,
  status ENUM('ready','processing','error','disabled') NOT NULL DEFAULT 'ready',
  created_by BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_media_type(type), INDEX idx_media_status(status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS live_inputs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  channel_id BIGINT UNSIGNED NULL,
  name VARCHAR(150) NOT NULL,
  protocol ENUM('hls','rtmp','srt','webrtc','other') NOT NULL DEFAULT 'hls',
  input_url TEXT NULL,
  stream_key VARCHAR(255) NULL,
  username VARCHAR(150) NULL,
  password_enc TEXT NULL,
  status ENUM('offline','online','disabled') NOT NULL DEFAULT 'offline',
  priority INT NOT NULL DEFAULT 0,
  notes TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_live_channel(channel_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS schedules (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  channel_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(190) NOT NULL,
  source_kind ENUM('media','live','ad_break','prayer','fallback') NOT NULL DEFAULT 'media',
  source_id BIGINT UNSIGNED NULL,
  starts_at DATETIME NOT NULL,
  ends_at DATETIME NULL,
  duration_seconds INT UNSIGNED NOT NULL DEFAULT 0,
  position INT NOT NULL DEFAULT 0,
  repeat_rule VARCHAR(100) NULL,
  interruptible TINYINT(1) NOT NULL DEFAULT 1,
  status ENUM('scheduled','playing','done','skipped','disabled') NOT NULL DEFAULT 'scheduled',
  notes TEXT NULL,
  created_by BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_schedule_channel_time(channel_id,starts_at,ends_at), INDEX idx_schedule_status(status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ads (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(190) NOT NULL,
  channel_id BIGINT UNSIGNED NULL,
  media_id BIGINT UNSIGNED NULL,
  placement ENUM('scheduled','pre','mid','post') NOT NULL DEFAULT 'scheduled',
  starts_on DATE NULL,
  ends_on DATE NULL,
  daily_start TIME NULL,
  daily_end TIME NULL,
  max_impressions INT UNSIGNED NULL,
  impressions INT UNSIGNED NOT NULL DEFAULT 0,
  status ENUM('active','paused','ended') NOT NULL DEFAULT 'active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tickers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  channel_id BIGINT UNSIGNED NULL,
  title VARCHAR(150) NOT NULL,
  text LONGTEXT NOT NULL,
  type ENUM('advertising','notice','breaking','prayer','general') NOT NULL DEFAULT 'general',
  direction ENUM('rtl','ltr') NOT NULL DEFAULT 'rtl',
  position ENUM('top','bottom') NOT NULL DEFAULT 'bottom',
  speed INT UNSIGNED NOT NULL DEFAULT 55,
  text_color VARCHAR(20) NOT NULL DEFAULT '#ffffff',
  background_color VARCHAR(30) NOT NULL DEFAULT '#0f172a',
  font_size INT UNSIGNED NOT NULL DEFAULT 16,
  opacity DECIMAL(3,2) NOT NULL DEFAULT 0.94,
  link_url TEXT NULL,
  starts_at DATETIME NULL,
  ends_at DATETIME NULL,
  priority INT NOT NULL DEFAULT 0,
  status ENUM('active','paused','ended') NOT NULL DEFAULT 'active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_ticker_channel(channel_id), INDEX idx_ticker_time(starts_at,ends_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS prayer_settings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  channel_id BIGINT UNSIGNED NOT NULL UNIQUE,
  country VARCHAR(100) NOT NULL DEFAULT 'Iran',
  province VARCHAR(100) NULL,
  city VARCHAR(100) NULL,
  latitude DECIMAL(10,7) NULL,
  longitude DECIMAL(10,7) NULL,
  calculation_method VARCHAR(100) NULL,
  fajr_time TIME NULL,
  sunrise_time TIME NULL,
  dhuhr_time TIME NULL,
  sunset_time TIME NULL,
  maghrib_time TIME NULL,
  midnight_time TIME NULL,
  pre_notice_minutes INT UNSIGNED NOT NULL DEFAULT 10,
  prayer_media_id BIGINT UNSIGNED NULL,
  action ENUM('ticker','play_media','both','none') NOT NULL DEFAULT 'ticker',
  enabled TINYINT(1) NOT NULL DEFAULT 0,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS api_keys (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  token_hash VARCHAR(255) NOT NULL,
  token_prefix VARCHAR(20) NOT NULL,
  scopes TEXT NULL,
  status TINYINT(1) NOT NULL DEFAULT 1,
  last_used_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  action VARCHAR(100) NOT NULL,
  entity_type VARCHAR(100) NULL,
  entity_id BIGINT UNSIGNED NULL,
  description TEXT NULL,
  ip_address VARCHAR(64) NULL,
  meta LONGTEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_audit_user(user_id), INDEX idx_audit_created(created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS viewer_events (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  channel_id BIGINT UNSIGNED NOT NULL,
  session_key VARCHAR(100) NOT NULL,
  event ENUM('open','heartbeat','close','error') NOT NULL DEFAULT 'open',
  user_agent VARCHAR(255) NULL,
  ip_hash VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_viewer_channel_time(channel_id,created_at), INDEX idx_viewer_session(session_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS channel_overrides (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  channel_id BIGINT UNSIGNED NOT NULL UNIQUE,
  title VARCHAR(190) NOT NULL,
  source_kind ENUM('media','live') NOT NULL,
  source_id BIGINT UNSIGNED NOT NULL,
  enabled TINYINT(1) NOT NULL DEFAULT 1,
  started_by BIGINT UNSIGNED NULL,
  started_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_override_enabled(enabled)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
