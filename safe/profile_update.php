<?php
require 'config.php';
must_login();
require_post_csrf();
$old = person(me());
$name = trim((string) ($_POST['full_name'] ?? $old['full_name']));
$email = trim((string) ($_POST['email'] ?? $old['email']));
$bio = (string) ($_POST['bio'] ?? $old['bio']);
if (!valid_name($name) || !valid_email($email) || strlen($bio) > 5000) {
    http_response_code(422);
    exit('Invalid profile fields');
}
try {
    db_execute(
        'UPDATE users SET full_name=?,email=?,bio=? WHERE id=?',
        'sssi',
        $name,
        $email,
        $bio,
        me(),
    );
} catch (mysqli_sql_exception $e) {
    http_response_code(409);
    exit('Email already exists or update failed');
}
redirect('edit_profile.php?saved=1');
