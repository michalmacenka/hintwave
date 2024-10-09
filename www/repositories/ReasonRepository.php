<?php

class ReasonRepository
{
  private Database $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function getReasonsByHintId(int $hintId): array
  {
    $sql = "SELECT value FROM reasons WHERE hint_id = ?";
    $results = $this->db->select($sql, [$hintId]);
    return array_column($results, 'value');
  }

  public function addReason(int $hintId, string $description): void
  {
    $sql = "INSERT INTO reasons (hint_id, description) VALUES (?, ?)";
    $this->db->insert($sql, [$hintId, $description]);
  }
}
