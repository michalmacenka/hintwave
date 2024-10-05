<?php

class Hint
{
  private $id;
  private $userId;
  private $title;
  private $description;
  private $category;
  private $createdAt;
  private $pros;
  private $cons;

  public function __construct($id, $userId, $title, $description, $category, $createdAt, $pros = [], $cons = [])
  {
    $this->id = $id;
    $this->userId = $userId;
    $this->title = $title;
    $this->description = $description;
    $this->category = $category;
    $this->createdAt = $createdAt;
    $this->pros = $pros;
    $this->cons = $cons;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function getCategory()
  {
    return $this->category;
  }

  public function getPros()
  {
    return $this->pros;
  }

  public function getCons()
  {
    return $this->cons;
  }
}
