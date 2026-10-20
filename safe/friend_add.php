<?php
require 'config.php';
must_login();
require_post_csrf();
$to = valid_id($_POST['friend_id'] ?? null);
if (!$to || $to === me() || !person($to)) {
    http_response_code(422);
    exit('Invalid connection');
}
db_execute('INSERT IGNORE INTO friendships(user_id,friend_id) VALUES(?,?)', 'ii', me(), $to);
redirect('profile.php?id=' . $to);
