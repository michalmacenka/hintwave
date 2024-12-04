<?php

/**
 * Reason Model Class
 * 
 * Represents a reason/advantage in the system
 * 
 * @package HintWave\Models
 */
class Reason
{
  /** @var int Reason ID */
  private int $id;

  /** @var int Related hint ID */
  private int $hint_id;

  /** @var string Reason text */
  private string $value;

  /**
   * Creates a new Reason instance
   * 
   * @param int $id Reason ID
   * @param int $hint_id Related hint ID
   * @param string $value Reason text
   */
  public function __construct(int $id, int $hint_id, string $value)
  {
    $this->id = $id;
    $this->hint_id = $hint_id;
    $this->value = $value;
  }

  /**
   * Get reason ID
   * 
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * Get related hint ID
   * 
   * @return int
   */
  public function getHintId(): int
  {
    return $this->hint_id;
  }

  /**
   * Get reason text
   * 
   * @return string
   */
  public function getValue(): string
  {
    return $this->value;
  }
}
