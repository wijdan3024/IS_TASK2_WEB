<?php
require 'includes.php';
$q = trim((string) ($_GET['q'] ?? ''));
if (strlen($q) > 500) {
    http_response_code(422);
    exit('Search too long');
}
head_page('Search');
?>
<div class="panel info-layout">
    <span class="tag">CAMPUS SEARCH</span>
    <h1>Search students and updates</h1>
    <form method="get" class="formrow">
        <input name="q" value="<?= e($q) ?>" maxlength="500" placeholder="Search your campus" />
        <button class="btn">Search</button>
    </form>
    <?php if ($q !== ''): ?>
    <h2>Search preview</h2>
    <p>
        You searched for:
        <?= e($q) ?>
    </p>
    <?php endif; ?>
</div>
<?php foot_page(); ?>
