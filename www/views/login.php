<?php
require_once __DIR__ . "/../common/CSRF.php";

?>
<form action="login.php" method="post">
  <label for="username">Username:</label>
  <input type="text" name="username" id="username" required>

  <label for="password">Password:</label>
  <input type="password" name="password" id="password" required>

  <?php CSRF::generate(); ?>

  <input type="submit" value="Login">
</form>