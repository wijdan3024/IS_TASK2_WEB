<?php require "includes.php";
require_admin();
$rows = all_rows(
    "SELECT employee_code,employee_name,department,salary FROM payroll ORDER BY employee_code",
);
head_page("Staff payroll");
?>
<div class="hero">
  <div>
    <h1>Staff payroll console</h1>
    <p>
      Campus administrative salary records — fictional demonstration data only.
    </p>
  </div>
  <div class="pill">Admin workflow demo</div>
</div>
<div class="panel">
  <h2>Payroll records</h2>
  <p class="muted">
    Only administrators can view and change payroll. The vulnerable login flow
    may still be bypassed through SQL injection.
  </p>
  <table>
    <tr>
      <th>Employee Code</th>
      <th>Name</th>
      <th>Department</th>
      <th>Salary (PKR)</th>
    </tr>
    <?php foreach (
    $rows
    as $r
): ?>
    <tr>
      <td><?= e($r["employee_code"]) ?></td>
      <td>
        <?= e(
    $r["employee_name"],
) ?>
      </td>
      <td><?= e($r["department"]) ?></td>
      <td>
        <?= number_format(
    (float) $r["salary"],
    2,
) ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>
<div class="panel info-layout">
  <h2>Payroll correction request</h2>
  <p class="muted">
    Administrator salary correction form. The employee-code field is
    intentionally concatenated into SQL for the local training lab.
  </p>
  <form method="post" action="payroll_update.php">
    <label>Employee code</label
    ><input name="employee_code" required value="EMP-001" /><label
      >Corrected salary (PKR)</label
    ><input name="salary" required value="90000" />
    <div class="spacer"></div>
    <button class="btn">Apply payroll correction</button>
  </form>
</div>
<?php foot_page(); ?>
