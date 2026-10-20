<?php require "config.php";
must_login();
$body = trim($_POST["body"] ?? "");
if ($_SERVER["REQUEST_METHOD"] === "POST" && $body !== "") {
    $id = (int) me();
    $stmt = mysqli_prepare($db, "INSERT INTO posts(user_id,body) VALUES(?,?)");
    mysqli_stmt_bind_param($stmt, "is", $id, $body);
    mysqli_stmt_execute($stmt);
}
redirect("index.php");
