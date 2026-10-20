<?php
require 'includes.php';
$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    require_post_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    if (
        !valid_name($name) ||
        !valid_email($email) ||
        strlen($password) < 10 ||
        strlen($password) > 200
    ) {
        $error = 'Enter valid name and email, and a password of at least 10 characters.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            db_execute(
                'INSERT INTO users(full_name,email,password_hash) VALUES(?,?,?)',
                'sss',
                $name,
                $email,
                $hash,
            );
            $created = db_one('SELECT id FROM users WHERE email=?', 's', $email);
            session_regenerate_id(true);
            $_SESSION = ['uid' => (int) $created['id'], 'csrf_token' => bin2hex(random_bytes(32))];
            redirect('index.php');
        } catch (mysqli_sql_exception $e) {
            $error = 'Registration failed; email may already exist.';
        }
    }
}
head_page('Register');
?>
<div class="panel form-card">
    <span class="tag">JOIN THE COMMUNITY</span>
    <h1>Make an account</h1>
    <?php if ($error) {
        echo alert_box($error, 'error');
    } ?>
    <form method="post" action="register.php">
        <?= csrf_field() ?>
        <label>Full name</label>
        <input name="name" maxlength="100" required />
        <label>Email</label>
        <input name="email" type="email" maxlength="190" required />
        <label>Password</label>
        <input
            name="password"
            type="password"
            minlength="10"
            maxlength="200"
            required
            autocomplete="new-password"
        />
        <div class="spacer"></div>
        <button class="btn" style="width: 100%">Register</button>
    </form>
</div>
<?php foot_page(); ?>
