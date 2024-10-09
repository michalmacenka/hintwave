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

// Handle POST request to add a new hint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Collect form data
  $title = $_POST['title'];
  $description = $_POST['description'];
  $categoryId = $_POST['category'];

  // Collect reasons (they can be empty or null)
  $reasons = array_filter($_POST['reasons'] ?? []); // Only include non-empty reasons

  // Call the method to add the hint
  $hintController->addHint($title, $description, $categoryId, $reasons);

  // Redirect to the index page
  header('Location: index.php');
  exit;
} else {
  // Display the add hint view with categories
  $hintController->showAddHintView();
}
