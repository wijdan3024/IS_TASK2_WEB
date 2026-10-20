<?php require "includes.php";
must_login();
$u = person(me());
$ok = isset($_GET["saved"]);
head_page("Edit profile");
?>
<div class="panel form-card"><h1>Edit your profile</h1><p class="muted">Your profile information is stored in the users table.</p><?php if (
    $ok
) {
    echo alert_box("Profile updated.");
} ?><form method="post" action="profile_update.php"><label>Display name</label><input name="full_name" value="<?= e(
    $u["full_name"],
) ?>"><label>Email address</label><input type="email" name="email" value="<?= e(
    $u["email"],
) ?>"><label>About me</label><textarea name="bio"><?= e(
    $u["bio"],
) ?></textarea><div class="spacer"></div><button class="btn">Save changes</button></form></div><?php foot_page(); ?>
