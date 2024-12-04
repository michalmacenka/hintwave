<?php

require_once __DIR__ . '/../models/Reason.php';

/**
 * Reason Repository Class
 * 
 * Handles database operations related to hint reasons
 * 
 * @package HintWave\Repositories
 */
class ReasonRepository
{
  /** @var Database Database connection instance */
  private Database $db;

  /**
   * Constructor
   * 
   * @param Database $db Database connection instance
   */
  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  /**
   * Get all reasons for a specific hint
   * 
   * @param int $hintId ID of hint to get reasons for
   * @return Reason[] Array of Reason objects
   */
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

  /**
   * Add a new reason for a hint
   * 
   * @param int $hintId ID of hint to add reason to
   * @param string $description Description of the reason
   * @return void
   */
  public function addReason(int $hintId, string $description): void
  {
    $sql = "INSERT INTO reasons (hint_id, description) VALUES (?, ?)";
    $this->db->insert($sql, [$hintId, $description]);
  }

  /**
   * Update all reasons for a hint
   * 
   * @param int $hintId ID of hint to update reasons for
   * @param array $reasons Array of reason strings
   * @return void
   * @throws Exception If database transaction fails
   */
  public function updateReasons($hintId, $reasons)
  {
    $this->db->beginTransaction();

    try {
      $sql = "DELETE FROM reasons WHERE hint_id = ?";
      $this->db->delete($sql, [$hintId]);

      $sql = "INSERT INTO reasons (hint_id, value) VALUES (?, ?)";
      foreach ($reasons as $reason) {
        $this->db->insert($sql, [$hintId, $reason]);
      }

      $this->db->commit();
    } catch (Exception $e) {
      $this->db->rollback();
      throw $e;
    }
  }
}
