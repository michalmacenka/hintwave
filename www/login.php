<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/repositories/AuthRepository.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . "/common/CSRF.php";


$db = new Database();
$authRepository = new AuthRepository($db);
$authController = new AuthController($authRepository);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $json = file_get_contents('php://input');
  $data = json_decode($json, true);

  if (!$data) {
    HTTPException::sendException(400, 'Invalid JSON data');
  }

  $username = $data['username'];
  $password = $data['password'];
  if (CSRF::validate($data['csrf_token'])) {
    $authController->login($username, $password);
  } else {
    HTTPException::sendException(400, 'CSRF token is invalid');
  }
} else {
  $authController->showLoginView();
}
