
<?php
// LOCAL LAB ONLY.
// Never deploy this deliberately vulnerable training build.

// ================= DATABASE CONFIGURATION =================

const DB_HOST = "localhost";
const DB_PORT = 3306;
const DB_NAME = "campusconnect_lab";
const DB_USER = "root";
const DB_PASS = "root";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $db = mysqli_connect(
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME,
        DB_PORT
    );

    mysqli_set_charset($db, "utf8mb4");

} catch (Throwable $e) {
    http_response_code(500);

    die(
        "Database connection failed. Verify MAMP, DB_PORT, and database import."
    );
}

// ================= SESSION CONFIGURATION =================

if (session_status() !== PHP_SESSION_ACTIVE) {

    // Deliberately vulnerable CSRF training environment.
    // SameSite=Lax does not separate localhost ports.

    session_set_cookie_params([
        "path" => "/",
        "httponly" => true,
        "samesite" => "Lax"
    ]);

    session_start();
}

// ================= AUTHENTICATION HELPERS =================

function me()
{
    return $_SESSION["uid"] ?? null;
}

// ================= HTML ESCAPING =================

function e($s)
{
    return htmlspecialchars(
        (string) ($s ?? ""),
        ENT_QUOTES | ENT_SUBSTITUTE,
        "UTF-8"
    );
}

// ================= DATABASE HELPERS =================

function one($sql)
{
    global $db;

    $r = mysqli_query($db, $sql);

    return mysqli_fetch_assoc($r) ?: null;
}

function all_rows($sql)
{
    global $db;

    $r = mysqli_query($db, $sql);

    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}

// ================= REDIRECT HELPER =================

function redirect($path)
{
    header("Location: " . $path);
    exit();
}

// ================= LOGIN CHECK =================

function must_login()
{
    if (!me()) {
        redirect("login.php?return=login");
    }
}

// ================= USER HELPER =================

function person($id)
{
    global $db;

    $id = (int) $id;

    return one("SELECT * FROM users WHERE id=$id");
}

// ================= PROFILE DISPLAY HELPERS =================

function card_name($u)
{
    return strtoupper(
        substr(
            (string) ($u["full_name"] ?? "U"),
            0,
            1
        )
    );
}

function alert_box($msg, $kind = "info")
{
    return '<div class="notice ' .
        e($kind) .
        '">' .
        e($msg) .
        "</div>\n";
}

// ================= ADMIN ROLE CHECK =================

// Payroll access is role-restricted even in the vulnerable build.
// The deliberately unsafe login query remains the SQL-injection entry point.

function is_admin()
{
    if (!me()) {
        return false;
    }

    $account = person(me());

    return $account && $account["role"] === "admin";
}

// ================= ADMIN AUTHORIZATION =================

function require_admin()
{
    if (!me()) {
        redirect("login.php");
    }

    if (!is_admin()) {
        http_response_code(403);

        die(
            "403 Forbidden: Payroll is restricted to administrator accounts."
        );
    }
}

// ================= PAYROLL AUTHORIZATION =================

// Server-side role check for payroll.
// The login query remains intentionally SQL-injectable in the vulnerable build.
// A test attacker may bypass login through that separate vulnerability.

function must_be_admin()
{
    if (!me()) {
        redirect("login.php");
    }

    $current = person(me());

    if (!$current || $current["role"] !== "admin") {

        http_response_code(403);

        die(
            '<h2>403 — Access denied</h2>
             <p>Admin login required for Staff Payroll.</p>
             <a href="index.php">Go to feed</a>'
        );
    }
}
