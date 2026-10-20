<?php require "config.php";
must_login();
$body = trim($_POST["body"] ?? "");
$post = (int) ($_POST["post_id"] ?? 0);
if ($body && $post) {
    $u = (int) me();
    $stmt = mysqli_prepare(
        $db,
        "INSERT INTO comments(post_id,user_id,body) VALUES(?,?,?)",
    );
    mysqli_stmt_bind_param($stmt, "iis", $post, $u, $body);
    mysqli_stmt_execute($stmt);
}
redirect("index.php");
