<?php
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/../repositories/AuthRepository.php";

$db = new Database();
$authRepository = new AuthRepository($db);
$authRepository->startSession();

/**
 * CSRF Protection Class
 * 
 * Handles Cross-Site Request Forgery protection
 * 
 * @package HintWave\Common
 */
class CSRF
{
  /**
   * Generate CSRF token and add it to the session
   * 
   * @return void
   */
  public static function generate()
  {
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    echo "<input name='csrf_token' value='$token' type='hidden'>";
  }

  /**
   * Validate CSRF token
   * 
   * @param string $csrf_token Token to validate
   * @return bool True if token is valid
   */
  public static function validate($csrf_token)
  {
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] == $csrf_token;
  }
}
