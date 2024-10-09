<?php

class Reason
{
  private int $id;
  private int $hint_id;
  private string $description;

  public function __construct(int $id, int $hint_id, string $description)
  {
    $this->id = $id;
    $this->hint_id = $hint_id;
    $this->description = $description;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getHintId(): int
  {
    return $this->hint_id;
  }

  public function getDescription(): string
  {
    return $this->description;
  }
}
