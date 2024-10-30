<?php
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/../repositories/AuthRepository.php";

$db = new Database();
$authRepository = new AuthRepository($db);
$authRepository->startSession();

class CSRF
{
  public static function generate()
  {
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    echo "<input name='csrf_token' value='$token' type= 'hidden'>";
  }

  public static function validate($csrf_token)
  {
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] == $csrf_token;
  }
}
