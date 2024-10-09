<?php
require_once __DIR__ . '/../models/Category.php';

class CategoryRepository
{
  private $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

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
