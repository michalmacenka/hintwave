<?php

/**
 * Category Model Class
 * 
 * Represents a category in the system
 * 
 * @package HintWave\Models
 */
class Category
{
  /** @var int Category ID */
  private $id;

  /** @var string Category name */
  private $name;

  /**
   * Creates a new Category instance
   * 
   * @param int $id Category ID
   * @param string $name Category name
   */
  public function __construct($id, $name)
  {
    $this->id = $id;
    $this->name = $name;
  }

  /**
   * Get category ID
   * 
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Get category name
   * 
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
}
