<?php

require_once './Database.php';
require_once './UsersRepository.php';

$database = new Database();
$usersRepository = new UsersRepository($database);
$users = $usersRepository->findAll();


?>

<h1>Users</h1>

<ul>
  <?php foreach ($users as $user) : ?>
    <li><?php echo $user['username']; ?></li>
  <?php endforeach; ?>
</ul>