<?php require "includes.php";
$id = $_GET["id"] ?? "";
$yes = null;
if ($id !== "") {
    try {
        $row = one("SELECT id FROM users WHERE id = $id LIMIT 1");
        $yes = $row !== null;
    } catch (Throwable $e) {
        $yes = false;
    }
}
head_page("Student lookup");
?>
<div class="panel info-layout">
  <span class="tag">STUDENT ID LOOKUP</span>
  <h1>Check a student ID</h1>
  <p class="muted">
    Public lookup confirms whether a student ID exists but never displays
    underlying database rows.
  </p>
  <form method="get" class="formrow">
    <input
      name="id"
      value="<?= e(
    $id,
) ?>"
      placeholder="e.g. 1"
    /><button class="btn">Check</button>
  </form>
  <?php if (
    $yes !== null
): ?>
  <div class="panel" style="margin-top: 20px; text-align: center">
    <h2>
      <?= $yes
    ? "Student record exists"
    : "No matching student record" ?>
    </h2>
    <p class="muted">This lookup only returns a yes/no answer.</p>
  </div>
  <?php endif; ?>
</div>
<?php foot_page(); ?>
