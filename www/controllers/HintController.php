<?php
require_once __DIR__ . '/../models/Hint.php';

/**
 * Hint Controller Class
 * 
 * Handles hint-related operations in the system
 * 
 * @package HintWave\Controllers
 */
class HintController
{
  /** @var HintRepository */
  private $hintRepository;
  /** @var CategoryRepository */
  private $categoryRepository;
  /** @var AuthRepository */
  private $authRepository;
  /** @var AuthController */
  private $authController;
  /** @var ReasonRepository */
  private $reasonRepository;

  /**
   * Constructor
   * 
   * @param HintRepository $hintRepository Repository for hint operations
   * @param CategoryRepository $categoryRepository Repository for category operations
   * @param AuthRepository $authRepository Repository for auth operations
   * @param AuthController $authController Controller for auth operations
   * @param ReasonRepository $reasonRepository Repository for reason operations
   */
  public function __construct(HintRepository $hintRepository, CategoryRepository $categoryRepository, AuthRepository $authRepository, AuthController $authController, ReasonRepository $reasonRepository)
  {
    $this->hintRepository = $hintRepository;
    $this->categoryRepository = $categoryRepository;
    $this->authRepository = $authRepository;
    $this->authController = $authController;
    $this->reasonRepository = $reasonRepository;
  }

  /**
   * Display the hints list view with pagination
   * 
   * @return void
   */
  public function showHintsView()
  {
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $perPage = 4;

    $totalHints = $this->hintRepository->getTotalHints();

    ob_start();
    $hints = $this->hintRepository->getAllHints($page, $perPage);
    $totalPages = ceil($totalHints / $perPage);
    include 'views/hints.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  /**
   * Display the add/edit hint form
   * 
   * @param int|null $editId Optional hint ID when editing
   * @return void
   */
  public function showAddHintView(?int $editId = null)
  {
    $this->authController->protectedRoute();

    $hintData = null;
    if ($editId) {
      $hintData = $this->hintRepository->getHintById($editId);
      if ($hintData == null) {
        header('Location: index.php');
        exit;
      }

      $currentUser = $this->authRepository->getUser();
      if ($hintData->getUser()->getId() !== $currentUser->getId() && !$currentUser->isAdmin()) {
        header('Location: index.php');
        exit;
      }
    }

    ob_start();
    $categories = $this->categoryRepository->getAllCategories();
    $hint = $hintData ?? null;
    include 'views/add_hint.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  /**
   * Add a new hint
   * 
   * @param string $title Hint title
   * @param string $description Hint description
   * @param int $categoryId Category ID
   * @param array $reasons Array of reason strings
   * @return void
   */
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

  /**
   * Update an existing hint
   * 
   * @param int $hintId ID of hint to update
   * @param string $title New hint title
   * @param string $description New hint description
   * @param int $categoryId New category ID
   * @param array $reasons New array of reason strings
   * @return void
   */
  public function updateHint($hintId, $title, $description, $categoryId, array $reasons)
  {
    $this->authController->protectedRoute();
    Validator::isInt($hintId, 'Hint ID');

    $hint = $this->hintRepository->getHintById($hintId);
    $currentUser = $this->authRepository->getUser();

    if (!$hint || ($hint->getUser()->getId() !== $currentUser->getId() && !$currentUser->isAdmin())) {
      header('Location: index.php');
      exit;
    }

    Validator::isString($title, 'Title', 1, 256);
    Validator::isString($description, 'Description', 1, 1024);
    Validator::isInt($categoryId, 'Category');
    Validator::isStringArray($reasons, 'Reasons', 2, 12, 3, 64);

    $this->hintRepository->updateHint($hintId, $title, $description, $categoryId);
    $this->reasonRepository->updateReasons($hintId, $reasons);

    HTTPException::sendException(200, 'Hint updated successfully');
  }

  /**
   * Display recommended hints view
   * 
   * @return void
   */
  public function showRecommendedView()
  {
    ob_start();
    $recommendedHints = $this->hintRepository->getRecommendedHintsByCategory();

    $this->authController->protectedRoute();
    include 'views/recommended.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  /**
   * Delete a hint
   * 
   * @param int $hintId ID of hint to delete
   * @return void
   */
  public function deleteHint(int $hintId)
  {
    $this->authController->protectedRoute();
    Validator::isInt($hintId, 'Hint ID');

    $currentUser = $this->authRepository->getUser();
    $hint = $this->hintRepository->getHintById($hintId);

    if (!$hint || ($hint->getUser()->getId() !== $currentUser->getId() && !$currentUser->isAdmin())) {
      header('Location: index.php');
      exit;
    }

    $this->hintRepository->deleteHint($hintId);
    HTTPException::sendException(200, 'Hint deleted successfully.');
  }

  /**
   * Display detailed view of a hint
   * 
   * @param int $hintId ID of hint to display
   * @return void
   */
  public function showHintDetail(int $hintId)
  {
    $hintData = null;
    if ($hintId) {
      $hintData = $this->hintRepository->getHintById($hintId);
      if ($hintData == null) {
        header('Location: index.php');
        exit;
      }
    }

    ob_start();
    $hint = $hintData ?? null;
    include 'views/hint_detail.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }
}
