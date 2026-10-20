<?php
require 'includes.php';
$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    require_post_csrf();
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    // Keep the authentication response uniform; avoid SQL injection and plaintext hashes.
    $user = valid_email($email)
        ? db_one('SELECT id,password_hash FROM users WHERE email=? LIMIT 1', 's', $email)
        : null;
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION = ['uid' => (int) $user['id']];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        redirect('index.php');
    }
    $error = 'Incorrect email or password.';
}
head_page('Log in');
?>
<div class="panel form-card">
    <span class="tag">WELCOME BACK</span>
    <h1>Sign in to CampusConnect</h1>
    <p class="muted">Safe authentication. Payroll requires an administrator account.</p>
    <?php if ($error) {
        echo alert_box($error, 'error');
    } ?>
    <form method="post" action="login.php">
        <?= csrf_field() ?>
        <label>Email address</label>
        <input type="email" name="email" required autocomplete="username" maxlength="190" />
        <label>Password</label>
        <input type="password" name="password" required autocomplete="current-password" />
        <div class="spacer"></div>
        <button class="btn" style="width: 100%">Log in</button>
    </form>
    <p class="muted">
        New here?
        <a href="register.php">Create an account</a>
    </p>
</div>
<?php foot_page(); ?>
