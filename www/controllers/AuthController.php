<?php
require_once __DIR__ . "/../common/Validator.php";
require_once __DIR__ . '/../common/ImageProcessor.php';


class AuthController
{
  private $authRepository;

  public function __construct(AuthRepository $authRepository)
  {
    $this->authRepository = $authRepository;
  }


  public function showRegisterView()
  {

    ob_start();
    $this->protectedRoute(blockLoggedUsers: true);


    include 'views/register.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  public function showLoginView()
  {

    ob_start();
    $this->protectedRoute(blockLoggedUsers: true);

    include 'views/login.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  public function register($username, DateTime $birth, $password, ?array $profileImage = null)
  {
    Validator::isString($username, 'Username', 3, 35);
    Validator::isPassword($password, 'Password');
    Validator::isDate($birth, 'Birth date');
    Validator::isProfileImage($profileImage, 'Profile image');

    if ($this->authRepository->getUserByUsername($username)) {
      HTTPException::sendException(400, 'Username already exists.');
    }

    if ((new DateTime())->diff($birth)->y < 15) {
      HTTPException::sendException(400, 'You must be at least 15 years old to register.');
    }

    $userId = $this->authRepository->registerUser($username, $birth->format('Y-m-d'), $password);

    if ($profileImage) {
      try {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $profileImage['data']));

        $tmpFile = tempnam(sys_get_temp_dir(), 'profile_');
        file_put_contents($tmpFile, $imageData);

        ImageProcessor::processProfileImage($tmpFile, $userId);

        unlink($tmpFile);
      } catch (Exception $e) {
        HTTPException::sendException(500, 'Failed to process profile image.');
      }
    }

    $this->login($username, $password);
  }

  public function login($username, $password)
  {
    Validator::isString($username, 'Username');
    Validator::isString($password, 'Password');

    $result = $this->authRepository->getUserByUsername($username);
    if ($result) {
      $passwordValid = password_verify($password, $result[0]["password"]);
      if ($result && $passwordValid) {
        $user = new User($result[0]["id"], $result[0]["username"], $result[0]["birth"], $result[0]["role"], $result[0]["created_at"]);
        $this->authRepository->startSession();
        $this->authRepository->setUser($user);
        HTTPException::sendException(200, 'User logged in successfully.');
      }
    }
    HTTPException::sendException(400, 'Invalid username or password.');
  }


  public function protectedRoute(bool $isAdminRoute = false, bool $blockLoggedUsers = false): void
  {
    $this->authRepository->startSession();
    $user = $this->authRepository->getUser();
    $isLoggedIn = $this->authRepository->isLoggedIn();

    if ($blockLoggedUsers && $isLoggedIn) {
      header('Location: index.php');
      exit;
    }

    if (!$isLoggedIn && !$blockLoggedUsers) {
      header('Location: login.php');
      exit;
    }

    if ($isLoggedIn) {
      $role = $user->getRole();
      if ($isAdminRoute && $role !== 1) {
        header('Location: index.php');
        exit;
      }
    }
  }
}
