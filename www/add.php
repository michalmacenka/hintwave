<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/repositories/HintRepository.php';
require_once __DIR__ . '/controllers/HintController.php';

$db = new Database();
$repository = new HintRepository($db);
$controller = new HintController($repository);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $category = $_POST['category'];
  $pros = explode(',', $_POST['pros']);
  $cons = explode(',', $_POST['cons']);

  $controller->addHint($title, $description, $pros, $cons, $category);
  header('Location: index.php');
} else {
  $controller->showAddHintView();
}
