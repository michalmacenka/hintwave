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
$hintController = new HintController($hintRepository, $categoryRepository, $authRepository, $authController);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $title = $_POST['title'];
  $description = $_POST['description'];
  $categoryId = $_POST['category'];

  $reasons = array_filter($_POST['reasons'] ?? []);

  if (CSRF::validate()) {
    $hintController->addHint($title, $description, $categoryId, $reasons);
  } else {
    exit('CSRF token is invalid');
  }

  header('Location: index.php');
  exit;
} else {
  $hintController->showAddHintView();
}
