<?php require "includes.php";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $hash = hash("sha256", $password);
    // VULNERABILITY: user input interpolated into an authentication SQL query.
    $sql = "SELECT id,full_name,role FROM users WHERE email='$email' AND password_hash='$hash' LIMIT 1";
    try {
        $user = one($sql);
        if ($user) {
            session_regenerate_id(true);
            $_SESSION["uid"] = $user["id"];
            redirect("index.php");
        }
        $error = "Incorrect email or password.";
    } catch (Throwable $ex) {
        $error = "Login query rejected by database.";
    }
}
head_page("Log in");
?>
<div class="panel form-card">
  <span class="tag">WELCOME BACK</span>
  <h1>Sign in to CampusConnect</h1>
  <p class="muted">
    Use your campus account. Payroll access requires the admin role.
  </p>
  <?php if (
    $error
) {
    echo alert_box($error, "error");
} ?>
  <form method="post">
    <label>Email address</label
    ><input
      type="text"
      name="email"
      required
      placeholder="alice@example.test"
    /><label>Password</label
    ><input type="password" name="password" placeholder="Your password" />
    <div class="spacer"></div>
    <button class="btn" style="width: 100%">Log in</button>
  </form>
  <p class="muted">New here? <a href="register.php">Create an account</a></p>
</div>
<?php foot_page(); ?>
