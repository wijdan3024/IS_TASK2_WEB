<?php
// Safe CampusConnect: separate database, sessions and cookie scope from vulnerable/.
// Change these LOCAL MAMP settings if your MySQL port or credentials differ.
const DB_HOST = 'localhost';
const DB_PORT = 3306;
const DB_NAME = 'campusconnect_safe';
const DB_USER = 'root'; // Local MAMP only; use a least-privileged DB user for deployment.
const DB_PASS = 'root'; // Local MAMP only; never put real secrets in Git.

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    mysqli_set_charset($db, 'utf8mb4');
} catch (Throwable $e) {
    http_response_code(500);
    exit(
        'Database connection failed. Import safe/database/campusconnect_safe.sql and check safe/config.php.'
    );
}

// Browser protections: no inline JS (preview.js is a separate file).
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header(
    "Content-Security-Policy: default-src 'self'; script-src 'self'; script-src-attr 'none'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; object-src 'none'; base-uri 'none'; frame-ancestors 'none'; form-action 'self'",
);
header('Cache-Control: no-store');

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_name('CAMPUSCONNECT_SAFE');
    $cookiePath =
        rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/safe/index.php')), '/') .
        '/';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $cookiePath,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

function me(): ?int
{
    return isset($_SESSION['uid']) ? (int) $_SESSION['uid'] : null;
}
function e($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function db_one(string $sql, string $types = '', ...$params): ?array
{
    global $db;
    $stmt = mysqli_prepare($db, $sql);
    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result) ?: null;
    mysqli_stmt_close($stmt);
    return $row;
}
function db_all(string $sql, string $types = '', ...$params): array
{
    global $db;
    $stmt = mysqli_prepare($db, $sql);
    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}
function db_execute(string $sql, string $types = '', ...$params): int
{
    global $db;
    $stmt = mysqli_prepare($db, $sql);
    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $changed = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $changed;
}
function redirect(string $path): never
{
    // Paths passed to this function are fixed application routes, not untrusted input.
    header('Location: ' . $path, true, 303);
    exit();
}
function must_login(): void
{
    if (!me() || !person(me())) {
        redirect('login.php');
    }
}
function person(int $id): ?array
{
    return db_one(
        'SELECT id,full_name,email,password_hash,role,program,bio,avatar_color,created_at FROM users WHERE id=?',
        'i',
        $id,
    );
}
function is_admin(): bool
{
    $u = me() ? person(me()) : null;
    return $u !== null && $u['role'] === 'admin';
}
function require_admin(): void
{
    must_login();
    if (!is_admin()) {
        http_response_code(403);
        exit('403 Forbidden: administrators only.');
    }
}
function card_name(array $u): string
{
    return strtoupper(substr((string) ($u['full_name'] ?? 'U'), 0, 1));
}
function alert_box(string $msg, string $kind = 'info'): string
{
    return '<div class="notice ' . e($kind) . '">' . e($msg) . '</div>';
}
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}
function require_post_csrf(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        http_response_code(405);
        exit('POST required');
    }
    $given = $_POST['csrf_token'] ?? '';
    if (!is_string($given) || !hash_equals(csrf_token(), $given)) {
        http_response_code(403);
        exit('403 Invalid CSRF token');
    }
}
function valid_id($value): ?int
{
    if (!is_scalar($value)) {
        return null;
    }
    $n = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $n === false ? null : (int) $n;
}
function valid_name($name): bool
{
    return is_string($name) && $name !== '' && strlen($name) <= 100;
}
function valid_email($email): bool
{
    return is_string($email) &&
        strlen($email) <= 190 &&
        (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}
function clean_avatar_color($color): string
{
    return is_string($color) && preg_match('/^#[a-fA-F0-9]{6}$/D', $color) ? $color : '#20a39e';
}
