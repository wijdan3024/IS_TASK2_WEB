<?php require "includes.php";
// VULNERABILITY: string-based filter directly inserted into a 4-column query.
$program = $_GET["program"] ?? "";
$sql = "SELECT id,full_name,program,bio FROM users WHERE program LIKE '%$program%' ORDER BY id";
$error = "";
try {
    $results = all_rows($sql);
} catch (Throwable $e) {
    $results = [];
    $error = "Database query error (invalid filter).";
}
head_page("Student directory");
?>
<div class="hero"><div><h1>Find your community</h1><p>Explore student profiles and discover people across campus.</p></div></div><div class="panel"><h2>Student directory</h2><form method="get" class="formrow"><input name="program" placeholder="Filter by program, e.g. Software Engineering" value="<?= e(
    $program,
) ?>"><button class="btn">Filter students</button></form></div><?php if (
    $error
) {
    echo alert_box($error, "error");
} ?><div class="usercards"><?php foreach (
    $results
    as $u
): ?><div class="panel"><div class="userline"><span class="avatar"><?= e(
    strtoupper(substr((string) $u["full_name"], 0, 1)),
) ?></span><div><strong><?= e($u["full_name"]) ?></strong><small><?= e(
    $u["program"],
) ?></small></div></div><p class="muted"><?= e($u["bio"]) ?></p><?php if (
    is_numeric($u["id"])
): ?><a class="btn outline" href="profile.php?id=<?= (int) $u[
    "id"
] ?>">View profile →</a><?php endif; ?></div><?php endforeach; ?></div><?php foot_page(); ?>
