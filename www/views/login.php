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

  <label for="password">Password:</label>
  <input type="password" name="password" id="password" required>

  <?php CSRF::generate(); ?>

  <input type="submit" value="Login">
  <div class="errMsg" id="globalErrMsg"></div>
</form>

<script src="/public/scripts/pages/login.js" type="module"></script>