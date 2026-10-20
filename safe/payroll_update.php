<?php
require 'includes.php';
require_admin();
require_post_csrf();
$code = trim((string) ($_POST['employee_code'] ?? ''));
$salary = trim((string) ($_POST['salary'] ?? ''));
if (
    !preg_match('/^EMP-[0-9]{3}$/D', $code) ||
    !preg_match('/^[0-9]{1,8}(?:\.[0-9]{1,2})?$/D', $salary) ||
    (float) $salary > 10000000
) {
    http_response_code(422);
    exit('Invalid employee code or salary');
}
$changed = db_execute(
    'UPDATE payroll SET salary=? WHERE employee_code=?',
    'ds',
    (float) $salary,
    $code,
);
head_page('Payroll update');
?>
<div class="panel info-layout">
    <span class="tag">PAYROLL UPDATE RESULT</span>
    <h1>
        Update executed. Changed records:
        <?= (int) $changed ?>
    </h1>
    <p class="muted">Only one matching employee code can be updated.</p>
    <a class="btn" href="payroll.php">Back to payroll</a>
</div>
<?php foot_page(); ?>
