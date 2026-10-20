<?php require "includes.php";
head_page("Announcements preview");
?>
<div class="panel info-layout">
  <span class="tag">INTERACTIVE PREVIEW</span>
  <h1>Live campus announcement preview</h1>
  <p class="muted">
    Change the text after # in the URL to preview the announcement without
    refreshing the server.
  </p>
  <div class="panel" style="background: #edf8f6">
    <h2>Announcement</h2>
    <div id="message" style="font-size: 23px; font-weight: 800">
      Welcome to CampusConnect
    </div>
  </div>
  <p class="tiny muted">
    The URL fragment is handled only by browser JavaScript.
  </p>
</div>
<script>
  function renderPreview() {
    let msg =
      decodeURIComponent(location.hash.substring(1)) ||
      "Welcome to CampusConnect";
    /* VULNERABILITY: untrusted URL fragment inserted as HTML */ document.getElementById(
      "message",
    ).innerHTML = msg;
  }
  window.addEventListener("hashchange", renderPreview);
  renderPreview();
</script>
<?php foot_page(); ?>
