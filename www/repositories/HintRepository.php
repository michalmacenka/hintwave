<?php

class HintRepository
{
  private Database $db;
  private CategoryRepository $categoryRepository;
  private ReasonRepository $reasonRepository;
  private AuthRepository $authRepository;

  public function __construct(Database $db, CategoryRepository $categoryRepository, ReasonRepository $reasonRepository, AuthRepository $authRepository)
  {
    $this->db = $db;
    $this->categoryRepository = $categoryRepository;
    $this->reasonRepository = $reasonRepository;
    $this->authRepository = $authRepository;
  }

  public function getAllHints()
  {
    $sql = "SELECT * FROM hints";
    $results = $this->db->select($sql);

    $hints = [];
    foreach ($results as $row) {
      $hintId = $row['id'];
      $reasons = $this->reasonRepository->getReasonsByHintId($hintId);
      $category = $this->categoryRepository->getCategoryById($row['category_id']);
      $user = $this->authRepository->getUserById($row['user_id']);

      $hints[] = new Hint($hintId, $user, $row['title'], $row['description'], $category, $reasons, $row['created_at']);
    }
    return $hints;
  }

  public function addHint(Hint $hint, array $reasons)
  {
    $sql = "INSERT INTO hints (user_id, title, description, category_id) VALUES (?, ?, ?, ?)";
    $this->db->insert($sql, [$hint->getUser()->getId(), $hint->getTitle(), $hint->getDescription(), $hint->getCategory()->getId()]);

    $hintId = $this->db->lastInsertId();

    foreach ($reasons as $r) {
      $this->db->insert("INSERT INTO reasons (hint_id, value) VALUES (?, ?)", [$hintId, $r]);
    }
  }


  public function getRecommendedHintsByCategory()
  {
    $sql = "SELECT DISTINCT id FROM categories";
    $categories = $this->db->select($sql);

    $recommendedHints = [];
    foreach ($categories as $categoryRow) {
      $categoryId = $categoryRow['id'];
      $sql = "SELECT * FROM hints WHERE category_id = :categoryId ORDER BY RAND() LIMIT 1";
      $hint = $this->db->select($sql, [':categoryId' => $categoryId]);

      if (!empty($hint)) {
        $category = $this->categoryRepository->getCategoryById($categoryId);
        $reasons = $this->reasonRepository->getReasonsByHintId($hint[0]['id']);
        $user = $this->authRepository->getUserById($hint[0]['user_id']);

        $recommendedHints[] = new Hint($hint[0]['id'], $user, $hint[0]['title'], $hint[0]['description'], $category, $reasons, $hint[0]['created_at']);
      }
    }

    return $recommendedHints;
  }
}
