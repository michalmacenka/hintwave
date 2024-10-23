<?php
require_once __DIR__ . '/../models/Hint.php';


class HintController
{
  private $hintRepository;
  private $categoryRepository;
  private $authRepository;
  private $authController;

  public function __construct(HintRepository $hintRepository, CategoryRepository $categoryRepository, AuthRepository $authRepository, AuthController $authController)
  {
    $this->hintRepository = $hintRepository;
    $this->categoryRepository = $categoryRepository;
    $this->authRepository = $authRepository;
    $this->authController = $authController;
  }

  public function showHintsView()
  {
    ob_start();
    $hints = $this->hintRepository->getAllHints();
    include 'views/hints.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  public function showAddHintView()
  {
    ob_start();
    $categories = $this->categoryRepository->getAllCategories();
    include 'views/add_hint.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  public function addHint($title, $description, $categoryId, array $reasons)
  {
    if (!$this->authRepository->isLoggedIn()) {
      header('Location: login.php');
      exit;
    }
    $user = $this->authRepository->getUser();

    $category = $this->categoryRepository->getCategoryById($categoryId);
    $hint = new Hint(0, $user, $title, $description, $category, [], date('Y-m-d H:i:s'));
    $this->hintRepository->addHint($hint, $reasons);
  }

  public function showRecommendedView()
  {
    ob_start();
    $recommendedHints = $this->hintRepository->getRecommendedHintsByCategory();

    $this->authController->protectedRoute();
    include 'views/recommended.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }
}
