<?php require "includes.php";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $pass = $_POST["password"] ?? "";
    if (
        !$name ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($pass) < 8
    ) {
        $error =
            "Please provide valid values (password at least 8 characters).";
    } else {
        try {
            $stmt = mysqli_prepare(
                $db,
                "INSERT INTO users(full_name,email,password_hash) VALUES(?,?,?)",
            );
            $h = hash("sha256", $pass);
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $h);
            mysqli_stmt_execute($stmt);
            $_SESSION["uid"] = mysqli_insert_id($db);
            redirect("index.php");
        } catch (Throwable $ex) {
            $error = "Registration failed; email may already exist.";
        }
    }
}
head_page("Register");
?>
<div class="panel form-card">
  <span class="tag">JOIN THE COMMUNITY</span>
  <h1>Make an account</h1>
  <?php if (
    $error
) {
    echo alert_box($error, "error");
} ?>
  <form method="post">
    <label>Full name</label><input required name="name" /><label>Email</label
    ><input required type="email" name="email" /><label>Password</label
    ><input required type="password" minlength="8" name="password" />
    <div class="spacer"></div>
    <button class="btn" style="width: 100%">Register</button>
  </form>
</div>
<?php foot_page(); ?>
