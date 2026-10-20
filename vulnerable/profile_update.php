<?php require "config.php";
must_login();
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("POST required");
}
// VULNERABILITY: no CSRF token, no Origin/Referer verification. Normal legitimate update functionality.
$uid = (int) me();
$old = person($uid);
$name = trim($_POST["full_name"] ?? $old["full_name"]);
$email = trim($_POST["email"] ?? $old["email"]);
$bio = $_POST["bio"] ?? $old["bio"];
if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    exit("Invalid profile fields");
}
$stmt = mysqli_prepare(
    $db,
    "UPDATE users SET full_name=?,email=?,bio=? WHERE id=?",
);
mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $bio, $uid);
try {
    mysqli_stmt_execute($stmt);
} catch (Throwable $e) {
    http_response_code(409);
    exit("Email exists or update failed");
}
redirect("edit_profile.php?saved=1");
