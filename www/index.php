<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/models/Hint.php';
require_once __DIR__ . '/repositories/HintRepository.php';
require_once __DIR__ . '/controllers/HintController.php';
require_once __DIR__ . '/repositories/CategoryRepository.php';
require_once __DIR__ . '/repositories/ReasonRepository.php';


$db = new Database();
$categoryRepository = new CategoryRepository($db);
$reasonRepository = new ReasonRepository($db);
$hintRepository = new HintRepository($db, $categoryRepository, $reasonRepository);
$hintController = new HintController($hintRepository, $categoryRepository);

$hintController->showRecommendedView();
