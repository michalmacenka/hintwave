<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/repositories/AuthRepository.php';

$db = new Database();
$authRepository = new AuthRepository($db);
$authController = new AuthController($authRepository);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];

  $birth = new DateTime($_POST['birth_year'] . '-' . $_POST['birth_month'] . '-' . $_POST['birth_day']);
  $authController->register($username, $birth, $password, $confirmPassword);
} else {
  $authController->showRegisterView();
}
