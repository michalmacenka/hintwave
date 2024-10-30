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

<form action="login.php" method="post">
  <div class="errMsg"></div>

  <label for="username">Username:</label>
  <input type="text" name="username" id="username" required>
  <div class="errMsg"></div>

  <label for="password">Password:</label>
  <input type="password" name="password" id="password" required>
  <div class="errMsg"></div>

  <?php CSRF::generate(); ?>

  <input type="submit" value="Login">
</form>

<script src="/public/scripts/pages/login.js" type="module"></script>