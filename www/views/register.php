<form action="register.php" method="post">
  <label for="username">Username:</label>
  <input type="text" name="username" id="username" required>

  <label for="password">Password:</label>
  <input type="password" name="password" id="password" required>

  <label for="confirm_password">Password confirm:</label>
  <input type="password" name="confirm_password" id="confirm_password" required>

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

  <input type="submit" value="Register">
</form>

<script>
  const daysInMonth = (month, year) => new Date(year, month, 0).getDate();

  const yearSelect = document.getElementById('birth_year');
  const monthSelect = document.getElementById('birth_month');
  const daySelect = document.getElementById('birth_day');

  function updateDays() {
    const year = parseInt(yearSelect.value);
    const month = parseInt(monthSelect.value);
    const numDays = daysInMonth(month, year);

    daySelect.innerHTML = '';

    for (let i = 1; i <= numDays; i++) {
      const option = document.createElement('option');
      option.value = i;
      option.text = i;
      daySelect.appendChild(option);
    }
  }

  yearSelect.addEventListener('change', updateDays);
  monthSelect.addEventListener('change', updateDays);

  window.onload = updateDays;
</script>