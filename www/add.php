<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/repositories/HintRepository.php';
require_once __DIR__ . '/controllers/HintController.php';
require_once __DIR__ . '/repositories/CategoryRepository.php';
require_once __DIR__ . '/repositories/ReasonRepository.php';

$db = new Database();
$categoryRepository = new CategoryRepository($db);
$reasonRepository = new ReasonRepository($db);
$hintRepository = new HintRepository($db, $categoryRepository, $reasonRepository);
$hintController = new HintController($hintRepository, $categoryRepository);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $title = $_POST['title'];
  $description = $_POST['description'];
  $categoryId = $_POST['category'];

  $reasons = array_filter($_POST['reasons'] ?? []);


  $hintController->addHint($title, $description, $categoryId, $reasons);

  header('Location: index.php');
  exit;
} else {
  $hintController->showAddHintView();
}
