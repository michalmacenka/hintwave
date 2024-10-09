<?php
require_once __DIR__ . '/../models/Hint.php';


class HintController
{
  private $hintRepository;
  private $categoryRepository;

  public function __construct(HintRepository $hintRepository, CategoryRepository $categoryRepository)
  {
    $this->hintRepository = $hintRepository;
    $this->categoryRepository = $categoryRepository;
  }

  public function showHintsView()
  {
    $hints = $this->hintRepository->getAllHints();
    ob_start();
    include 'views/hints.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  public function showAddHintView()
  {
    $categories = $this->categoryRepository->getAllCategories();
    ob_start();
    include 'views/add_hint.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  public function addHint($title, $description, $categoryId, array $reasons)
  {
    $category = $this->categoryRepository->getCategoryById($categoryId);
    $hint = new Hint(0, 1, $title, $description, $category, [], date('Y-m-d H:i:s'));
    $this->hintRepository->addHint($hint, $reasons); // Ensure your repository method is ready to handle this
  }

  public function showRecommendedView()
  {
    $recommendedHints = $this->hintRepository->getRecommendedHintsByCategory();
    ob_start();
    include 'views/recommended.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }
}
