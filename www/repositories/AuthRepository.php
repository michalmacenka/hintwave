<?php


class AuthRepository
{
  private $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function registerUser($username, $birth, $password)
  {
    $sql = "INSERT INTO users (username, password, birth) VALUES (?, ?, ?)";
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $this->db->insert($sql, [$username, $hash, $birth]);
  }

  public function getUserByUsername(string $username)
  {
    $sql = "SELECT * FROM users WHERE username = ?";
    return $this->db->select($sql, [$username]);
  }
}
