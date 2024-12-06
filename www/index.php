<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/models/Hint.php';
require_once __DIR__ . '/repositories/HintRepository.php';
require_once __DIR__ . '/controllers/HintController.php';
require_once __DIR__ . '/repositories/CategoryRepository.php';
require_once __DIR__ . '/repositories/ReasonRepository.php';
require_once __DIR__ . '/repositories/AuthRepository.php';
require_once __DIR__ . '/controllers/AuthController.php';


$db = new Database();
$categoryRepository = new CategoryRepository($db);
$reasonRepository = new ReasonRepository($db);
$authRepository = new AuthRepository($db);
$authController = new AuthController($authRepository);

$hintRepository = new HintRepository($db, $categoryRepository, $reasonRepository, $authRepository);
$hintController = new HintController($hintRepository, $categoryRepository, $authRepository, $authController, $reasonRepository);

if (isset($_GET['id']) && $_GET['id'] !== null) {
  $hintId = (int)$_GET['id'];
  if ($hintId <= 0) {
    header('Location: /~macenmic/hints.php');
    exit;
  }
  $hintController->showHintDetail($hintId);
} else {
  $hintController->showRecommendedView();
}
