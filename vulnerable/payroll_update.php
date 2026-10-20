<?php require "includes.php";
require_admin();
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect("payroll.php");
}
$code = $_POST["employee_code"] ?? "";
$salary = $_POST["salary"] ?? "";
// VULNERABILITY: employee_code concatenated directly into UPDATE WHERE; admin role is checked.
// Salary must be numeric to keep test records predictable; SQLi still works via employee_code.
if (!is_numeric($salary) || $salary < 0 || $salary >
10000000) { http_response_code(422); die("Invalid salary"); } $sql = "UPDATE
payroll SET salary=" . (float) $salary . " WHERE employee_code='$code'"; try {
mysqli_query($db, $sql); $changed = mysqli_affected_rows($db); $note = "Update
executed. Changed records: $changed"; } catch (Throwable $e) { $note = "SQL
update failed. Check your input syntax."; } head_page("Payroll update"); ?>
<div class="panel info-layout">
  <span class="tag">PAYROLL UPDATE RESULT</span>
  <h1>
    <?= e(
    $note,
) ?>
  </h1>
  <p class="muted">
    Check the payroll table before and after the operation. All salary figures
    are fictional.
  </p>
  <a class="btn" href="payroll.php">Back to payroll</a>
</div>
<?php foot_page(); ?>
