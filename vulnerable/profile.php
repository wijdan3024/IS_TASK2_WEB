<?php require "includes.php";
$id = (int) ($_GET["id"] ?? (me() ?? 1));
$u = person($id);
if (!$u) {
    http_response_code(404);
    die("Profile not found");
}
$isFriend = false;
if (me()) {
    $isFriend = (bool) one(
        "SELECT id FROM friendships WHERE user_id=" .
            (int) me() .
            " AND friend_id=" .
            $id,
    );
}
$friends = one("SELECT COUNT(*) AS c FROM friendships WHERE user_id=" . $id);
head_page("Profile");
?>
<div class="panel">
  <div class="userline">
    <?= avatar(
    $u,
    "large",
) ?>
    <div>
      <span class="tag"
        ><?= e(
    ucfirst($u["role"]),
) ?>
        profile</span
      >
      <h1 style="margin: 8px 0 3px">
        <?= e(
     $u["full_name"],
 ) ?>
      </h1>
      <span class="muted"
        ><?= e($u["program"]) ?>
        ·
        <?= e(
     $u["email"],
 ) ?></span
      >
    </div>
  </div>
  <div class="stats" style="margin-top: 20px">
    <div class="stat">
      <b
        ><?= intval(
    $friends["c"] ?? 0,
) ?></b
      ><small>Connections</small>
    </div>
    <div class="stat">
      <b
        ><?= e(
    $u["role"],
) ?></b
      ><small>Campus role</small>
    </div>
  </div>
  <h2>About me</h2>
  <div class="bio">
    <?php // VULNERABILITY: stored profile bio echoed as HTML, not encoded.
echo nl2br($u["bio"] ?? ""); ?>
  </div>
  <div class="spacer"></div>
  <?php if (
    me() &&
    (int) me() !== $id
): ?>
  <form method="post" action="friend_add.php">
    <input type="hidden" name="friend_id" value="<?= $id ?>" /><button
      class="btn"
    >
      <?= $isFriend
    ? "Connected ✓"
    : "Add connection" ?>
    </button>
  </form>
  <?php elseif (
    me() &&
    (int) me() === $id
): ?><a class="btn" href="edit_profile.php">Edit my profile</a><?php else: ?><a
    class="btn"
    href="login.php"
    >Log in to connect</a
  ><?php endif; ?>
</div>
<div class="panel">
  <h2>How this works</h2>
  <p class="muted">
    Student bios are stored in MySQL. A profile visit fetches the saved bio from
    the database and displays it to the visitor.
  </p>
</div>
<?php foot_page(); ?>
