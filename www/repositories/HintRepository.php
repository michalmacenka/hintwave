<?php

/**
 * Hint Repository Class
 * 
 * Handles database operations related to hints
 * 
 * @package HintWave\Repositories
 */
class HintRepository
{
  /** @var Database Database connection instance */
  private Database $db;

  /** @var CategoryRepository Category repository instance */
  private CategoryRepository $categoryRepository;

  /** @var ReasonRepository Reason repository instance */
  private ReasonRepository $reasonRepository;

  /** @var AuthRepository Auth repository instance */
  private AuthRepository $authRepository;

  /**
   * Constructor
   * 
   * @param Database $db Database connection
   * @param CategoryRepository $categoryRepository Category repository
   * @param ReasonRepository $reasonRepository Reason repository
   * @param AuthRepository $authRepository Auth repository
   */
  public function __construct(Database $db, CategoryRepository $categoryRepository, ReasonRepository $reasonRepository, AuthRepository $authRepository)
  {
    $this->db = $db;
    $this->categoryRepository = $categoryRepository;
    $this->reasonRepository = $reasonRepository;
    $this->authRepository = $authRepository;
  }

  /**
   * Retrieve all hints with pagination
   * 
   * @param int $page Page number
   * @param int $perPage Number of hints per page
   * @return Hint[] Array of Hint objects
   */
  public function getAllHints(int $page = 1, int $perPage = 6): array
  {
    $offset = ($page - 1) * $perPage;
    $sql = "SELECT * FROM hints ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $results = $this->db->select($sql, [$perPage, $offset]);

    $hints = [];
    foreach ($results as $row) {
      $reasons = $this->reasonRepository->getReasonsByHintId($row['id']);
      $category = $this->categoryRepository->getCategoryById($row['category_id']);
      $user = $this->authRepository->getUserById($row['user_id']);

      $hints[] = new Hint($row['id'], $user, $row['title'], $row['description'], $category, $reasons, $row['created_at']);
    }
    return $hints;
  }

  /**
   * Get total number of hints in database
   * 
   * @return int Total number of hints
   */
  public function getTotalHints(): int
  {
    $sql = "SELECT COUNT(*) as count FROM hints";
    $result = $this->db->select($sql);
    return (int)$result[0]['count'];
  }

  /**
   * Get a hint by its ID
   * 
   * @param int $hintId ID of hint to retrieve
   * @return Hint|null Hint object if found, null otherwise
   */
  public function getHintById(int $hintId)
  {
    $sql = "SELECT * FROM hints WHERE id = ?";
    $results = $this->db->select($sql, [$hintId]);
    if (empty($results)) {
      return null;
    }

    $reasons = $this->reasonRepository->getReasonsByHintId($hintId);
    $category = $this->categoryRepository->getCategoryById($results[0]['category_id']);
    $user = $this->authRepository->getUserById($results[0]['user_id']);

    $hint = new Hint($hintId, $user, $results[0]['title'], $results[0]['description'], $category, $reasons, $results[0]['created_at']);
    return $hint;
  }

  /**
   * Add a new hint to the database
   * 
   * @param Hint $hint Hint object to add
   * @param array $reasons Array of reason strings
   * @return void
   */
  public function addHint(Hint $hint, array $reasons)
  {
    $sql = "INSERT INTO hints (user_id, title, description, category_id) VALUES (?, ?, ?, ?)";
    $this->db->insert($sql, [$hint->getUser()->getId(), $hint->getTitle(), $hint->getDescription(), $hint->getCategory()->getId()]);

    $hintId = $this->db->lastInsertId();

    foreach ($reasons as $r) {
      $this->db->insert("INSERT INTO reasons (hint_id, value) VALUES (?, ?)", [$hintId, $r]);
    }
  }

  /**
   * Delete a hint from the database
   * 
   * @param int $hintId ID of hint to delete
   * @return void
   */
  public function deleteHint(int $hintId)
  {
    $sql = "DELETE FROM hints WHERE id = ?";
    $this->db->delete($sql, [$hintId]);
  }

  /**
   * Update an existing hint
   * 
   * @param int $hintId ID of hint to update
   * @param string $title New title
   * @param string $description New description
   * @param int $categoryId New category ID
   * @return void
   */
  public function updateHint(int $hintId, string $title, string $description, int $categoryId)
  {
    $sql = "UPDATE hints SET title = ?, description = ?, category_id = ? WHERE id = ?";
    $this->db->update($sql, [$title, $description, $categoryId, $hintId]);
  }

  /**
   * Get one random hint from each category
   * 
   * @return Hint[] Array of recommended hints, one per category
   */
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
