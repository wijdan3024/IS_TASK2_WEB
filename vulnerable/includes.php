<?php
require_once __DIR__ . "/config.php";
function head_page($title = "CampusConnect")
{
    $me = me() ? person(me()) : null;
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>' .
        e($title) .
        ' · CampusConnect</title><link rel="stylesheet" href="assets/style.css"></head><body>';
    echo '<div class="warning">SECURITY TRAINING LAB — INTENTIONALLY VULNERABLE — LOCALHOST ONLY</div><header><div class="wrap nav"><a class="logo" href="index.php"><span class="logo-icon">C</span> Campus<span>Connect</span></a><nav><a href="index.php">Feed</a><a href="directory.php">Students</a><a href="search.php">Search</a><a href="announcement.php">Preview</a><a href="check.php">Lookup</a>' .
        ($me && $me["role"] === "admin"
            ? '<a href="payroll.php">Staff Payroll</a>'
            : "") .
        '</nav><div class="account">';
    if ($me) {
        echo '<a href="profile.php?id=' .
            (int) $me["id"] .
            '">' .
            e($me["full_name"]) .
            '</a> <a class="navbtn" href="logout.php">Logout</a>';
    } else {
        echo '<a href="login.php">Log in</a><a class="navbtn" href="register.php">Join now</a>';
    }
    echo '</div></div></header><main class="wrap">';
}
function foot_page()
{
    echo '</main><footer class="wrap">CampusConnect · Local Security Assignment Lab · Fictional users and payroll records</footer></body></html>';
}
function avatar($user, $size = "")
{
    return '<span class="avatar ' .
        e($size) .
        '" style="background:' .
        e($user["avatar_color"] ?? "#20a39e") .
        '">' .
        e(card_name($user)) .
        "</span>";
}
