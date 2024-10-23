<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/repositories/AuthRepository.php';
require_once __DIR__ . '/controllers/AuthController.php';

$db = new Database();
$authRepository = new AuthRepository($db);
$authController = new AuthController($authRepository);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $authController->login($username, $password);
} else {
  $authController->showLoginView();
}
