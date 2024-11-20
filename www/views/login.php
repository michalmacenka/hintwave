<?php
require_once __DIR__ . "/../common/CSRF.php";
?>


<h1 class="mb-xl text-center">Login</h1>
<form>
  <div class="form-group">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" placeholder="Username" required>
  </div>

  <div class="form-group">
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" placeholder="Password" required>
  </div>

  <?php CSRF::generate(); ?>

  <input type="submit" value="Login">
  <div class="errMsg" id="globalErrMsg"></div>
</form>

<script src="/public/scripts/pages/login.js" type="module"></script>