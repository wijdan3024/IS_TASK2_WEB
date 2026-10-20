<?php
require 'includes.php';
$program = trim((string) ($_GET['program'] ?? ''));
if (strlen($program) > 100) {
    http_response_code(422);
    exit('Invalid program filter');
}
$results = db_all(
    'SELECT id,full_name,program,bio FROM users WHERE program LIKE ? ORDER BY id LIMIT 100',
    's',
    '%' . $program . '%',
);
head_page('Student directory');
?>
<div class="hero">
    <div>
        <h1>Find your community</h1>
        <p>Explore student profiles and discover people across campus.</p>
    </div>
</div>
<div class="panel">
    <h2>Student directory</h2>
    <form method="get" class="formrow">
        <input
            name="program"
            maxlength="100"
            placeholder="Filter by program, e.g. Software Engineering"
            value="<?= e($program) ?>"
        />
        <button class="btn">Filter students</button>
    </form>
</div>
<div class="usercards">
    <?php foreach ($results as $u): ?>
    <div class="panel">
        <div class="userline">
            <span class="avatar"><?= e(card_name($u)) ?></span>
            <div>
                <strong><?= e($u['full_name']) ?></strong>
                <small><?= e($u['program']) ?></small>
            </div>
        </div>
        <p class="muted"><?= nl2br(e($u['bio'])) ?></p>
        <a class="btn outline" href="profile.php?id=<?= (int) $u['id'] ?>">View profile →</a>
    </div>
    <?php endforeach; ?>
</div>
<?php foot_page(); ?>
