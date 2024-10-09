<?php

class Hint
{
  private int $id;
  private int $user_id;
  private string $title;
  private string $description;
  private Category $category;
  /**
   * @var Reason[] Array of Reason objects
   */
  private array $reasons;
  private $created_at;

  public function __construct($id, $user_id, $title, $description, $category, $reasons, $created_at)
  {
    $this->id = $id;
    $this->user_id = $user_id;
    $this->title = $title;
    $this->description = $description;
    $this->category = $category;
    $this->reasons = $reasons;
    $this->created_at = $created_at;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getUserId()
  {
    return $this->user_id;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function getReasons()
  {
    return $this->reasons;
  }

  public function getCategory()
  {
    return $this->category;
  }

  public function getCreatedAt()
  {
    return $this->created_at;
  }
}
