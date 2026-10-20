<?php
require 'includes.php';
$id = valid_id($_GET['id'] ?? (me() ?? 1));
if (!$id || !($u = person($id))) {
    http_response_code(404);
    exit('Profile not found');
}
$isFriend = false;
if (me()) {
    $isFriend = (bool) db_one(
        'SELECT id FROM friendships WHERE user_id=? AND friend_id=?',
        'ii',
        me(),
        $id,
    );
}
$friends = db_one('SELECT COUNT(*) AS c FROM friendships WHERE user_id=?', 'i', $id);
head_page('Profile');
?>
<div class="panel">
    <div class="userline">
        <?= avatar($u, 'large') ?>
        <div>
            <span class="tag">
                <?= e(ucfirst($u['role'])) ?>
                profile
            </span>
            <h1 style="margin: 8px 0 3px"><?= e($u['full_name']) ?></h1>
            <span class="muted">
                <?= e($u['program']) ?>
                ·
                <?= e($u['email']) ?>
            </span>
        </div>
    </div>
    <div class="stats" style="margin-top: 20px">
        <div class="stat">
            <b><?= (int) ($friends['c'] ?? 0) ?></b>
            <small>Connections</small>
        </div>
        <div class="stat">
            <b><?= e($u['role']) ?></b>
            <small>Campus role</small>
        </div>
    </div>
    <h2>About me</h2>
    <div class="bio"><?= nl2br(e($u['bio'] ?? '')) ?></div>
    <div class="spacer"></div>
    <?php if (me() && me() !== $id): ?>
    <form method="post" action="friend_add.php">
        <?= csrf_field() ?>
        <input type="hidden" name="friend_id" value="<?= $id ?>" />
        <button class="btn">
            <?= $isFriend ? 'Connected ✓' : 'Add connection' ?>
        </button>
    </form>
    <?php elseif (me() && me() === $id): ?>
    <a class="btn" href="edit_profile.php">Edit my profile</a>
    <?php else: ?>
    <a class="btn" href="login.php">Log in to connect</a>
    <?php endif; ?>
</div>
<div class="panel">
    <h2>How this works</h2>
    <p class="muted">
        Profile bios are displayed as text, not executable HTML. Editing your own profile requires a
        verified CSRF token.
    </p>
</div>
<?php foot_page(); ?>
