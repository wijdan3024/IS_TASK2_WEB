<?php
require 'includes.php';
must_login();
$u = person(me());
head_page('Edit profile');
?>
<div class="panel form-card">
    <h1>Edit your profile</h1>
    <p class="muted">Only your logged-in profile can be edited.</p>
    <?php if (isset($_GET['saved'])) {
        echo alert_box('Profile updated.');
    } ?>
    <form method="post" action="profile_update.php">
        <?= csrf_field() ?>
        <label>Display name</label>
        <input name="full_name" maxlength="100" required value="<?= e($u['full_name']) ?>" />
        <label>Email address</label>
        <input type="email" name="email" maxlength="190" required value="<?= e($u['email']) ?>" />
        <label>About me</label>
        <textarea name="bio" maxlength="5000"><?= e($u['bio']) ?></textarea>
        <div class="spacer"></div>
        <button class="btn">Save changes</button>
    </form>
</div>
<?php foot_page(); ?>
