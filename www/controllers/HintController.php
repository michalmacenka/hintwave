<?php

class HintController
{
  private $repository;

  public function __construct(HintRepository $repository)
  {
    $this->repository = $repository;
  }

  public function showHintsView()
  {
    $hints = $this->repository->getAllHints();
    ob_start();
    include 'views/hints.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  public function showAddHintView()
  {
    ob_start();
    include 'views/add_hint.php';
    $content = ob_get_clean();
    include 'views/layout.php';
  }

  public function addHint($title, $description, $pros, $cons, $category)
  {
    $this->repository->addHint($title, $description, $pros, $cons, $category);
    header('Location: index.php');
    exit;
  }
}
