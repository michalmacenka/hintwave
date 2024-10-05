<?php

class HintRepository
{
  private $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function getAllHints()
  {
    $sql = "SELECT * FROM hints";
    $results = $this->db->select($sql);

    $hints = [];
    foreach ($results as $row) {
      $hintId = $row['id'];
      $pros = $this->getProsByHintId($hintId);
      $cons = $this->getConsByHintId($hintId);

      $hints[] = new Hint($row['id'], $row['user_id'], $row['title'], $row['description'], $row['category'], $row['created_at'], $pros, $cons);
    }
    return $hints;
  }

  private function getProsByHintId($hintId)
  {
    $sql = "SELECT description FROM pros WHERE hint_id = ?";
    $results = $this->db->select($sql, [$hintId]);
    return array_column($results, 'description');
  }

  private function getConsByHintId($hintId)
  {
    $sql = "SELECT description FROM cons WHERE hint_id = ?";
    $results = $this->db->select($sql, [$hintId]);
    return array_column($results, 'description');
  }

  public function addHint($title, $description, $pros, $cons, $category)
  {
    $sql = "INSERT INTO hints (title, description, category, user_id) VALUES (?, ?, ?, ?)";
    $userId = 1;
    $this->db->insert($sql, [$title, $description, $category, $userId]);

    $hintId = $this->db->lastInsertId();

    foreach ($pros as $pro) {
      $this->db->insert("INSERT INTO pros (hint_id, description) VALUES (?, ?)", [$hintId, $pro]);
    }

    foreach ($cons as $con) {
      $this->db->insert("INSERT INTO cons (hint_id, description) VALUES (?, ?)", [$hintId, $con]);
    }
  }

  public function getPros($hintId)
  {
    $sql = 'SELECT description FROM pros WHERE hint_id = ?';
    $results = $this->db->select($sql, [$hintId]);
    return array_column($results, 'description');
  }

  public function getCons($hintId)
  {
    $sql = 'SELECT description FROM cons WHERE hint_id = ?';
    $results = $this->db->select($sql, [$hintId]);
    return array_column($results, 'description');
  }
}
