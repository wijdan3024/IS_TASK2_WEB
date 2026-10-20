<?php require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Live Preview - CampusConnect</title>
    <style>
      body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f3f6fa;
        color: #142b43;
      }
      header {
        background: #fff;
        padding: 25px 10%;
        font-size: 24px;
        font-weight: bold;
        border-bottom: 1px solid #ddd;
      }
      main {
        max-width: 900px;
        margin: 70px auto;
        padding: 35px;
        background: white;
        border-radius: 15px;
      }
      #announcement {
        padding: 30px;
        background: #eaf8f5;
        border-radius: 10px;
        font-size: 22px;
      }
    </style>
  </head>
  <body>
    <header>CampusConnect</header>

    <main>
      <h1>Live Announcement Preview</h1>
      <p>Campus announcements are previewed here.</p>

      <h2>Announcement</h2>
      <div id="announcement"></div>
    </main>

    <script>
      function updatePreview() {
        const text =
          decodeURIComponent(window.location.hash.substring(1)) ||
          "Welcome to CampusConnect";

        // INTENTIONALLY VULNERABLE:
        document.getElementById("announcement").innerHTML = text;
      }

      window.addEventListener("hashchange", updatePreview);
      updatePreview();
    </script>
  </body>
</html>
