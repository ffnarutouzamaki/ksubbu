<?php
/**
 * K. Subbu Digital Archive — Site Configuration
 * Built and tuned for InfinityFree (Apache + PHP + MySQL, free tier).
 * Fill in your real MySQL hosting details below.
 */

// ----- DATABASE CREDENTIALS (edit these) -----
// Find these in your InfinityFree vPanel -> MySQL Databases.
// DB_HOST looks like sqlXXX.infinityfree.com, DB_NAME/DB_USER look like if0_XXXXXXXX...
define('DB_HOST', 'sql208.infinityfree.com');
define('DB_NAME', 'if0_42561046_ksubbu');
define('DB_USER', 'if0_42561046');
define('DB_PASS', 'C2JWIV76Jq');

// ----- SITE SETTINGS -----
define('SITE_NAME', 'K. Subbu Digital Archive');
define('SITE_URL',  'https://www.ksubbu-archive.in'); // change to your live domain / InfinityFree subdomain
define('UPLOAD_DIR', __DIR__ . '/uploads');
define('UPLOAD_URL', 'uploads');

// InfinityFree enforces a hard 10 MB per-file limit on every account (it cannot be
// raised via php.ini or .htaccess on the free tier). Keep this at 10 — it only
// controls the friendly message shown in the admin panel.
define('MAX_UPLOAD_MB', 10);

// ----- SECURITY -----
// Random string reserved for future use. Change it to any long random text.
define('APP_SECRET', 'change-this-to-a-long-random-string');

date_default_timezone_set('Asia/Kolkata');

// ----- SESSIONS -----
// Some shared hosts (InfinityFree included) point PHP's default session
// storage at a shared system folder that isn't writable for every account,
// which shows up as broken logins or "session_start(): open(...) failed"
// errors. The fix is simple: store session files inside a folder this
// account definitely owns and can write to, instead of the system default.
$sessionDir = __DIR__ . '/storage/sessions';
if (!is_dir($sessionDir)) {
    @mkdir($sessionDir, 0755, true);
}
if (is_dir($sessionDir) && is_writable($sessionDir)) {
    session_save_path($sessionDir);
}
// If that folder isn't writable for some reason, PHP quietly falls back to
// its normal default — the line above never blocks session_start() below.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/functions.php';
