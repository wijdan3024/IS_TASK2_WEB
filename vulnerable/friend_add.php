<?php require "config.php";
must_login();
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("POST only");
}
$to = (int) ($_POST["friend_id"] ?? 0);
$from = (int) me();
if (!$to || $to === $from || !person($to)) {
    http_response_code(422);
    exit("Invalid connection");
}
// VULNERABILITY: no CSRF token or same-origin request validation.
$stmt = mysqli_prepare(
    $db,
    "INSERT IGNORE INTO friendships(user_id,friend_id) VALUES(?,?)",
);
mysqli_stmt_bind_param($stmt, "ii", $from, $to);
mysqli_stmt_execute($stmt);
redirect("profile.php?id=" . $to);
