<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/repositories/AuthRepository.php';
require_once __DIR__ . "/common/CSRF.php";

$db = new Database();
$authRepository = new AuthRepository($db);
$authController = new AuthController($authRepository);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];

  $birth = new DateTime($_POST['birth_year'] . '-' . $_POST['birth_month'] . '-' . $_POST['birth_day']);
  if (CSRF::validate()) {
    $authController->register($username, $birth, $password, $confirmPassword);
  } else {
    exit('CSRF token is invalid');
  }
} else {
  $authController->showRegisterView();
}
