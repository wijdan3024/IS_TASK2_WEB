<?php
require_once __DIR__ . '/config.php';
function head_page(string $title = 'CampusConnect'): void
{
    $current = me() ? person(me()) : null;
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>' .
        e($title) .
        ' · CampusConnect</title><link rel="stylesheet" href="assets/style.css"></head><body>';
    echo '<div class="warning safe-warning">SAFE VERSION — XSS / CSRF / SQL INJECTION DEFENSES ENABLED</div><header><div class="wrap nav"><a class="logo" href="index.php"><span class="logo-icon">C</span> Campus<span>Connect</span></a><nav><a href="index.php">Feed</a><a href="directory.php">Students</a><a href="search.php">Search</a><a href="preview.php">Preview</a><a href="lookup.php">Lookup</a>' .
        ($current && $current['role'] === 'admin'
            ? '<a href="payroll.php">Staff Payroll</a>'
            : '') .
        '</nav><div class="account">';
    if ($current) {
        echo '<a href="profile.php?id=' .
            (int) $current['id'] .
            '">' .
            e($current['full_name']) .
            '</a>';
        echo '<form method="post" action="logout.php" class="inline-form">' .
            csrf_field() .
            '<button class="navbtn" type="submit">Logout</button></form>';
    } else {
        echo '<a href="login.php">Log in</a><a class="navbtn" href="register.php">Join now</a>';
    }
    echo '</div></div></header><main class="wrap">';
}
function foot_page(): void
{
    echo '</main><footer class="wrap">CampusConnect · Safe build · Fictional users and payroll records</footer></body></html>';
}
function avatar(array $user, string $size = ''): string
{
    return '<span class="avatar ' .
        e($size) .
        '" style="background:' .
        e(clean_avatar_color($user['avatar_color'] ?? '')) .
        '">' .
        e(card_name($user)) .
        '</span>';
}
