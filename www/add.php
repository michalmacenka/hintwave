<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/repositories/HintRepository.php';
require_once __DIR__ . '/controllers/HintController.php';
require_once __DIR__ . '/repositories/CategoryRepository.php';
require_once __DIR__ . '/repositories/ReasonRepository.php';
require_once __DIR__ . '/repositories/AuthRepository.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . "/common/CSRF.php";


$db = new Database();
$categoryRepository = new CategoryRepository($db);
$reasonRepository = new ReasonRepository($db);
$authRepository = new AuthRepository($db);
$authController = new AuthController($authRepository);
$hintRepository = new HintRepository($db, $categoryRepository, $reasonRepository, $authRepository);
$hintController = new HintController($hintRepository, $categoryRepository, $authRepository, $authController, $reasonRepository);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $json = file_get_contents('php://input');
  $data = json_decode($json, true);

  if (!$data) {
    HTTPException::sendException(400, 'Invalid JSON data');
  }

  $title = trim($data['title']);
  $description = trim($data['description']);
  $categoryId = trim($data['category']);

  $reasons = array_map('trim', array_filter($data['reasons'] ?? []));

  if (CSRF::validate($data['csrf_token'])) {
    $hintId = isset($data['hint_id']) ? (int)$data['hint_id'] : null;
    if ($hintId) {
      $hintController->updateHint(
        $hintId,
        $title,
        $description,
        $categoryId,
        $reasons
      );
    } else {
      $hintController->addHint($title, $description, $categoryId, $reasons);
    }
  } else {
    HTTPException::sendException(400, 'CSRF token is invalid');
  }

  exit;
} else {
  $editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
  $hintController->showAddHintView($editId);
}
