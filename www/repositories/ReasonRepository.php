<?php

require_once __DIR__ . '/../models/Reason.php';

class ReasonRepository
{
  private Database $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function getReasonsByHintId(int $hintId): array
  {
    $sql = "SELECT * FROM reasons WHERE hint_id = ?";
    $results = $this->db->select($sql, [$hintId]);

    $reasons = [];
    foreach ($results as $row) {
      $reasons[] = new Reason($row['id'], $row['hint_id'], $row['value']);
    }
    return $reasons;
  }

  public function addReason(int $hintId, string $description): void
  {
    $sql = "INSERT INTO reasons (hint_id, description) VALUES (?, ?)";
    $this->db->insert($sql, [$hintId, $description]);
  }
}
