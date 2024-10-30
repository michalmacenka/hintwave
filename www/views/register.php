<?php
require_once __DIR__ . "/../common/CSRF.php";
?>

<style>
  .errMsg {
    color: red;
    display: none;
    margin-bottom: 10px;
  }
</style>

<form>

  <label for="username">Username:</label>
  <input type="text" name="username" id="username" required>
  <div class="errMsg"></div>

  <label for="password">Password:</label>
  <input type="password" name="password" id="password" required>
  <div class="errMsg"></div>

  <label for="confirm_password">Password confirm:</label>
  <input type="password" name="confirm_password" id="confirm_password" required>
  <div class="errMsg"></div>

  <div class="birthDateGroup">
    <label for="birth_year">Year of Birth:</label>
    <select name="birth_year" id="birth_year" required>
      <?php
      $currentYear = date("Y");
      for ($i = $currentYear; $i >= 1920; $i--) {
        echo "<option value=\"$i\">$i</option>";
      }
      ?>
    </select>

    <label for="birth_month">Month of Birth:</label>
    <select name="birth_month" id="birth_month" required>
      <?php
      for ($i = 1; $i <= 12; $i++) {
        $monthName = date("F", mktime(0, 0, 0, $i, 10)); // Get month name
        echo "<option value=\"$i\">$monthName</option>";
      }
      ?>
    </select>

    <label for="birth_day">Day of Birth:</label>
    <select name="birth_day" id="birth_day" required>
      <?php
      for ($i = 1; $i <= 31; $i++) {
        echo "<option value=\"$i\">$i</option>";
      }
      ?>
    </select>
    <div class="errMsg"></div>
  </div>

  <?php CSRF::generate(); ?>

  <input type="submit" value="Register">
  <div class="errMsg" id="globalErrMsg"></div>

</form>

<script src="/public/scripts/pages/register.js" type="module"></script>