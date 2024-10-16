<?php

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
    include 'views/register.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  public function register($username, DateTime $birth, $password, $confirmPassword)
  {

    if ($password !== $confirmPassword) {
      throw new Exception('Passwords do not match');
    }

    if ($this->authRepository->getUserByUsername($username)) {
      throw new Exception('User already exists');
    }

    if ((new DateTime())->diff($birth)->y < 15) {
      throw new Exception('Age must be at least 15');
    }

    $this->authRepository->registerUser($username, $birth->format('Y-m-d'), $password);
  }
}
