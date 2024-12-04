<?php

/**
 * Hint Model Class
 * 
 * Represents a hint in the system with associated user, category, and reasons
 * 
 * @package HintWave\Models
 */
class Hint
{
  /** @var int Hint ID */
  private int $id;

  /** @var User Associated user */
  private User $user;

  /** @var string Hint title */
  private string $title;

  /** @var string Hint description */
  private string $description;

  /** @var Category Associated category */
  private Category $category;

  /** @var Reason[] Array of associated reasons */
  private array $reasons;

  /** @var string Creation timestamp */
  private $created_at;

  /**
   * Creates a new Hint instance
   * 
   * @param int $id Hint ID
   * @param User $user Associated user
   * @param string $title Hint title
   * @param string $description Hint description
   * @param Category $category Associated category
   * @param Reason[] $reasons Array of reasons
   * @param string $created_at Creation timestamp
   */
  public function __construct($id, $user, $title, $description, $category, $reasons, $created_at)
  {
    $this->id = $id;
    $this->user = $user;
    $this->title = $title;
    $this->description = $description;
    $this->category = $category;
    $this->reasons = $reasons;
    $this->created_at = $created_at;
  }

  /**
   * Get hint ID
   * 
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Get associated user
   * 
   * @return User
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * Get hint title
   * 
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Get hint description
   * 
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Get hint reasons
   * 
   * @return Reason[]
   */
  public function getReasons()
  {
    return $this->reasons;
  }

  /**
   * Get associated category
   * 
   * @return Category
   */
  public function getCategory()
  {
    return $this->category;
  }

  /**
   * Get creation timestamp
   * 
   * @return string
   */
  public function getCreatedAt()
  {
    return $this->created_at;
  }

  /**
   * Render hint component
   * 
   * @return void
   */
  public function render(): void
  {
    include "components/Hint.php";
  }
}
