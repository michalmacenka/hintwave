<?php
require_once __DIR__ . '/../models/User.php';


class AuthRepository
{
  private $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function registerUser($username, $birth, $password): int
  {
    $sql = "INSERT INTO users (username, password, birth) VALUES (?, ?, ?)";
    $hash = password_hash($password, PASSWORD_BCRYPT);

    return $this->db->insert($sql, [$username, $hash, $birth]);
  }

  public function getUserByUsername(string $username)
  {
    $sql = "SELECT * FROM users WHERE username = ?";
    return $this->db->select($sql, [$username]);
  }

  public function getUserById(int $id)
  {
    $sql = "SELECT * FROM users WHERE id = ?";
    $results = $this->db->select($sql, [$id]);


    if (count($results) === 0) {
      return null;
    }

    $row = $results[0];
    return new User($row['id'], $row['username'], $row['birth'], $row['role'], $row['created_at']);
  }

  public function startSession()
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }



  public function logout()
  {
    $this->startSession();
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
  }

  public function isLoggedIn()
  {
    $this->startSession();
    return isset($_SESSION['user']);
  }

  public function getUser()
  {
    $this->startSession();
    if (isset($_SESSION['user'])) {
      $user = $_SESSION['user'];
      return new User($user['id'], $user['username'], $user['birth'], $user['role'], $user['created_at']);
    }
  }

  public function setUser(User $user)
  {
    $this->startSession();

    $_SESSION['user'] = [
      'id' => $user->getId(),
      'username' => $user->getUsername(),
      'birth' => $user->getBirth(),
      'role' => $user->getRole(),
      'created_at' => $user->getCreatedAt()
    ];
  }
}
