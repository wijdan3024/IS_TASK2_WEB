<?php
require 'includes.php';
head_page('Live Preview');
?>
<div class="panel info-layout">
    <span class="tag">INTERACTIVE PREVIEW</span>
    <h1>Live Announcement Preview</h1>
    <p class="muted">Change the text after # in the URL to preview an announcement safely.</p>
    <div class="panel" style="background: #edf8f6">
        <h2>Announcement</h2>
        <div id="announcement" style="font-size: 23px; font-weight: 800">
            Welcome to CampusConnect
        </div>
    </div>
    <p class="tiny muted">
        URL fragments are displayed as text — HTML tags and scripts cannot run.
    </p>
</div>
<script src="assets/preview.js" defer></script>
<?php foot_page(); ?>
