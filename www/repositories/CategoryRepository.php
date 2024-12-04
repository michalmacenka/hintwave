<?php
require_once __DIR__ . '/../models/Category.php';

/**
 * Category Repository Class
 * 
 * Handles database operations related to categories
 * 
 * @package HintWave\Repositories
 */
class CategoryRepository
{
  /** @var Database Database connection instance */
  private $db;

  /**
   * Constructor
   * 
   * @param Database $db Database connection
   */
  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  /**
   * Retrieve all categories from the database
   * 
   * @return Category[] Array of Category objects
   */
  public function getAllCategories()
  {
    $sql = "SELECT * FROM categories";
    $results = $this->db->select($sql);

    $categories = [];
    foreach ($results as $row) {
      $categories[] = new Category($row['id'], $row['name']);
    }

    return $categories;
  }

  /**
   * Retrieve a category by its ID
   * 
   * @param int $id Category ID
   * @return Category|null Category object or null if not found
   */
  public function getCategoryById($id)
  {
    $sql = "SELECT * FROM categories WHERE id = ?";
    $results = $this->db->select($sql, [$id]);

    if (count($results) === 0) {
      return null;
    }

    $row = $results[0];
    return new Category($row['id'], $row['name']);
  }
}
