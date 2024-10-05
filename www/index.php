<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/models/Hint.php';
require_once __DIR__ . '/repositories/HintRepository.php';
require_once __DIR__ . '/controllers/HintController.php';

$db = new Database();
$repository = new HintRepository($db);
$controller = new HintController($repository);

$controller->showHintsView();
