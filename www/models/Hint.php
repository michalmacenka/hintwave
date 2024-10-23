<?php

class Hint
{
  private int $id;
  private User $user;
  private string $title;
  private string $description;
  private Category $category;
  /**
   * @var Reason[] Array of Reason objects
   */
  private array $reasons;
  private $created_at;

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

  public function getId()
  {
    return $this->id;
  }

  public function getUser()
  {
    return $this->user;
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

  public function render(): void
  {
    include "components/Hint.php";
  }
}
