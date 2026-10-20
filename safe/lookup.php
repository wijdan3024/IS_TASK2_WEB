<?php
require 'includes.php';
$id = trim((string) ($_GET['id'] ?? ''));
$yes = null;
if ($id !== '') {
    $studentId = valid_id($id);
    $yes =
        $studentId !== null &&
        db_one('SELECT id FROM users WHERE id=? LIMIT 1', 'i', $studentId) !== null;
}
head_page('Student lookup');
?>
<div class="panel info-layout">
    <span class="tag">STUDENT ID LOOKUP</span>
    <h1>Check a student ID</h1>
    <p class="muted">This page reveals only whether a valid numeric student ID exists.</p>
    <form method="get" class="formrow">
        <input name="id" inputmode="numeric" value="<?= e($id) ?>" placeholder="e.g. 1" />
        <button class="btn">Check</button>
    </form>
    <?php if ($yes !== null): ?>
    <div class="panel" style="margin-top: 20px; text-align: center">
        <h2><?= $yes ? 'Student record exists' : 'No matching student record' ?></h2>
        <p class="muted">No database details are returned.</p>
    </div>
    <?php endif; ?>
</div>
<?php foot_page(); ?>
