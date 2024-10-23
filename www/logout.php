<?php
require_once __DIR__ . '/common/Database.php';
require_once __DIR__ . '/repositories/AuthRepository.php';

$db = new Database();
$authRepository = new AuthRepository($db);

$authRepository->logout();
