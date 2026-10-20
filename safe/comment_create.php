<?php
require 'config.php';
must_login();
require_post_csrf();
$body = trim((string) ($_POST['body'] ?? ''));
$postId = valid_id($_POST['post_id'] ?? null);
if (
    $body === '' ||
    strlen($body) > 1500 ||
    !$postId ||
    !db_one('SELECT id FROM posts WHERE id=?', 'i', $postId)
) {
    http_response_code(422);
    exit('Invalid comment');
}
db_execute('INSERT INTO comments(post_id,user_id,body) VALUES(?,?,?)', 'iis', $postId, me(), $body);
redirect('index.php');
