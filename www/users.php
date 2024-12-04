<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/repositories/AuthRepository.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . "/common/CSRF.php";

$db = new Database();
$authRepository = new AuthRepository($db);
$authController = new AuthController($authRepository);

// Ensure only admins can access this page
$authController->protectedRoute(isAdminRoute: true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $json = file_get_contents('php://input');
  $data = json_decode($json, true);

  if (!$data) {
    HTTPException::sendException(400, 'Invalid JSON data');
  }

  if (CSRF::validate($data['csrf_token'])) {
    $userId = (int)$data['userId'];
    $newRole = (int)$data['role'];
    $authController->updateUserRole($userId, $newRole);
  } else {
    HTTPException::sendException(400, 'CSRF token is invalid');
  }
} else {
  $authController->showAdminUsersView();
}
