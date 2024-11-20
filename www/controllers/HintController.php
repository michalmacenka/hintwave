<?php
require_once __DIR__ . '/../models/Hint.php';


class HintController
{
  private $hintRepository;
  private $categoryRepository;
  private $authRepository;
  private $authController;
  private $reasonRepository;
  public function __construct(HintRepository $hintRepository, CategoryRepository $categoryRepository, AuthRepository $authRepository, AuthController $authController, ReasonRepository $reasonRepository)
  {
    $this->hintRepository = $hintRepository;
    $this->categoryRepository = $categoryRepository;
    $this->authRepository = $authRepository;
    $this->authController = $authController;
    $this->reasonRepository = $reasonRepository;
  }

  public function showHintsView()
  {
    ob_start();
    $hints = $this->hintRepository->getAllHints();
    include 'views/hints.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  public function showAddHintView(?int $editId = null)
  {
    $this->authController->protectedRoute();

    $hintData = null;
    if ($editId) {
      $hintData = $this->hintRepository->getHintById($editId);
      if ($hintData == null) {
        HTTPException::sendException(404, 'Hint not found.');
      }

      if ($hintData->getUser()->getId() !== $this->authRepository->getUser()->getId()) {
        HTTPException::sendException(403, 'Forbidden');
      }
    }

    ob_start();
    $categories = $this->categoryRepository->getAllCategories();
    $hint = $hintData ?? null;
    include 'views/add_hint.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }


  public function addHint($title, $description, $categoryId, array $reasons)
  {
    $this->authController->protectedRoute();


    Validator::isString($title, 'Title', 1, 256);
    Validator::isString($description, 'Description', 1, 1024);
    Validator::isInt($categoryId, 'Category');
    Validator::isStringArray($reasons, 'Reasons', 2, 12, 3, 64);


    $user = $this->authRepository->getUser();
    if ($user === null) {
      HTTPException::sendException(400, 'User does not exist.');
    }

    $category = $this->categoryRepository->getCategoryById($categoryId);

    if ($category === null) {
      HTTPException::sendException(400, 'Category does not exist.');
    }


    $hint = new Hint(0, $user, $title, $description, $category, [], date('Y-m-d H:i:s'));
    $this->hintRepository->addHint($hint, $reasons);
    HTTPException::sendException(200, 'Hint added successfully.');
  }

  public function updateHint($hintId, $title, $description, $categoryId, array $reasons)
  {
    $this->authController->protectedRoute();

    $hint = $this->hintRepository->getHintById($hintId);
    if (!$hint || $hint->getUser()->getId() !== $this->authRepository->getUser()->getId()) {
      HTTPException::sendException(403, 'Unauthorized to edit this hint');
    }

    Validator::isString($title, 'Title', 1, 256);
    Validator::isString($description, 'Description', 1, 1024);
    Validator::isInt($categoryId, 'Category');
    Validator::isStringArray($reasons, 'Reasons', 2, 12, 3, 64);

    $this->hintRepository->updateHint($hintId, $title, $description, $categoryId);
    $this->reasonRepository->updateReasons($hintId, $reasons);

    HTTPException::sendException(200, 'Hint updated successfully');
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

  public function deleteHint(int $hintId)
  {
    Validator::isInt($hintId, 'Hint ID');
    $user = $this->authRepository->getUser();

    if ($user === null || !$user->isAdmin()) {
      HTTPException::sendException(403, 'Forbidden');
    }

    $this->authController->verifyUserRole($user->getId(), 1);
    $this->hintRepository->deleteHint($hintId);
    HTTPException::sendException(200, 'Hint deleted successfully.');
  }
}
