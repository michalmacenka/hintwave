<?php

class Reason
{
  private int $id;
  private int $hint_id;
  private string $value;

  public function __construct(int $id, int $hint_id, string $value)
  {
    $this->id = $id;
    $this->hint_id = $hint_id;
    $this->value = $value;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getHintId(): int
  {
    return $this->hint_id;
  }

  public function getValue(): string
  {
    return $this->value;
  }
}
