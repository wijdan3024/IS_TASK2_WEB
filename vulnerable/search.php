<?php require "includes.php";
$q = $_GET["q"] ?? "";
head_page("Search");
?>
<div class="panel info-layout">
  <span class="tag">CAMPUS SEARCH</span>
  <h1>Search students and updates</h1>
  <form method="get" class="formrow">
    <input
      name="q"
      value="<?= e(
    $q,
) ?>"
      placeholder="Search your campus"
    /><button class="btn">Search</button>
  </form>
  <?php if (
    $q !== ""
): ?>
  <h2>Search preview</h2>
  <p>
    You searched for:
    <?php /* VULNERABILITY: raw reflected output */ echo $q; ?>
  </p>
  <?php endif; ?>
</div>
<?php foot_page(); ?>
