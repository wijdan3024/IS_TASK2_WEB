<?php
require 'config.php';
must_login();
require_post_csrf();
$body = trim((string) ($_POST['body'] ?? ''));
if ($body === '' || strlen($body) > 5000) {
    http_response_code(422);
    exit('Invalid post');
}
db_execute('INSERT INTO posts(user_id,body) VALUES(?,?)', 'is', me(), $body);
redirect('index.php');
