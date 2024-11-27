<?php
require_once __DIR__ . "/../common/CSRF.php";
?>



<h1 class="mb-xl text-center">Register</h1>
<form>

  <div class="form-group">
    <label for="username" class="required">Username</label>
    <input type="text" name="username" id="username" required>
    <div class="errMsg"></div>
  </div>

  <div class="form-group">
    <label for="password" class="required">Password</label>
    <input type="password" name="password" id="password" required>
    <div class="errMsg"></div>
  </div>

  <div class="form-group">
    <label for="confirm_password" class="required">Password confirm</label>
    <input type="password" name="confirm_password" id="confirm_password" required>
    <div class="errMsg"></div>
  </div>

  <div class="form-group">
    <label for="birth_year" class="required">Birth date</label>
    <div class="birthDateGroup flex gap-md">
      <select name="birth_year" id="birth_year" required>
        <?php
        $currentYear = date("Y");
        for ($i = $currentYear; $i >= 1920; $i--) {
          echo "<option value=\"$i\">$i</option>";
        }
        ?>
      </select>

      <select name="birth_month" id="birth_month" required>
        <?php
        for ($i = 1; $i <= 12; $i++) {
          $monthName = date("F", mktime(0, 0, 0, $i, 10)); // Get month name
          echo "<option value=\"$i\">$monthName</option>";
        }
        ?>
      </select>

      <select name="birth_day" id="birth_day" required>
        <?php
        for ($i = 1; $i <= 31; $i++) {
          echo "<option value=\"$i\">$i</option>";
        }
        ?>
      </select>
      <div class="errMsg"></div>
    </div>
  </div>

  <div class="form-group">
    <label for="profile_image">Profile Image</label>
    <div class="drop-area" id="dropArea">
      <input type="file"
        name="profile_image"
        id="profile_image"
        accept="image/jpeg,image/png,image/webp"
        class="file-input hidden">
      <div class="drop-message">
        <i class='bx bx-upload'></i>
        <p>Drag and drop your image here or</p>
        <button type="button" class="browse-btn">Browse files</button>
        <p class="file-info"></p>
      </div>
      <div class="preview-area hidden">
        <img src="" alt="Preview" id="imagePreview">
        <button type="button" class="remove-btn">
          <i class='bx bx-x'></i>
        </button>
      </div>
    </div>
    <div class="errMsg"></div>
  </div>

  <?php CSRF::generate(); ?>

  <input type="submit" value="Register">
  <div class="errMsg" id="globalErrMsg"></div>

</form>

<script src="/public/scripts/pages/register.js" type="module"></script>
<link rel="stylesheet" href="/public/styles/scoped/register.css">