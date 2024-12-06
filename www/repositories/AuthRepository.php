<?php
require_once __DIR__ . '/../models/User.php';

/**
 * Authentication Repository Class
 * 
 * Handles user authentication, registration and session management
 * 
 * @package HintWave\Repositories
 */
class AuthRepository
{
  /** @var Database Database connection instance */
  private $db;

  /**
   * Creates a new AuthRepository instance
   * 
   * @param Database $db Database connection instance
   */
  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  /**
   * Register a new user
   * 
   * @param string $username Username
   * @param string $birth Birth date
   * @param string $password Password
   * @return int ID of newly created user
   */
  public function registerUser($username, $birth, $password): int
  {
    $sql = "INSERT INTO users (username, password, birth) VALUES (?, ?, ?)";
    $hash = password_hash($password, PASSWORD_BCRYPT);

    return $this->db->insert($sql, [$username, $hash, $birth]);
  }

  /**
   * Get user by username
   * 
   * @param string $username Username to search for
   * @return array|null User data array or null if not found
   */
  public function getUserByUsername(string $username)
  {
    $sql = "SELECT * FROM users WHERE username = ?";
    return $this->db->select($sql, [$username]);
  }

  /**
   * Get user by ID
   * 
   * @param int $id User ID
   * @return User|null User object or null if not found
   */
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

  /**
   * Start PHP session if not already started
   * 
   * @return void
   */
  public function startSession()
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  /**
   * Log out current user and redirect to home page
   * 
   * @return void
   */
  public function logout()
  {
    $this->startSession();
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
  }

  /**
   * Check if user is currently logged in
   * 
   * @return bool True if user is logged in, false otherwise
   */
  public function isLoggedIn()
  {
    $this->startSession();
    if (!isset($_SESSION['user'])) {
      return false;
    }
    
    $user = $this->getUserById($_SESSION['user']['id']);
    return $user !== null;
  }

  /**
   * Get currently logged in user
   * 
   * @return User|null User object if logged in, null otherwise
   */
  public function getUser()
  {
    $this->startSession();
    if (isset($_SESSION['user'])) {
      $user = $this->getUserById($_SESSION['user']['id']);
      return $user;
    }
  }

  /**
   * Set user session data
   * 
   * @param User $user User object to store in session
   * @return void
   */
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

  /**
   * Get all users from database
   * 
   * @return User[] Array of all users
   */
  public function getAllUsers(): array
  {
    $sql = "SELECT * FROM users ORDER BY created_at DESC";
    $results = $this->db->select($sql);

    $users = [];
    foreach ($results as $row) {
      $users[] = new User(
        $row['id'],
        $row['username'],
        $row['birth'],
        $row['role'],
        $row['created_at']
      );
    }
    return $users;
  }

  /**
   * Update user role
   * 
   * @param int $userId User ID to update
   * @param int $newRole New role value
   */
  public function updateUserRole(int $userId, int $newRole)
  {
    $sql = "UPDATE users SET role = :newRole WHERE id = :userId";
    $this->db->update($sql, [':newRole' => $newRole, ':userId' => $userId]);
    HTTPException::sendException(200, 'User role updated successfully.');
  }

  /**
   * Delete a user
   * 
   * @param int $userId User ID to delete
   * @return bool Success status
   */
  public function deleteUser(int $userId): bool
  {
    try {
      // Delete user's profile image if it exists
      $user = $this->getUserById($userId);
      if ($user) {
        $imagePath = __DIR__ . '/../public/uploads/profiles/' . $user->getId() . '.webp';
        if (file_exists($imagePath)) {
          unlink($imagePath);
        }
      }

      // Delete the user from database
      $sql = "DELETE FROM users WHERE id = ?";
      $this->db->delete($sql, [$userId]);
      return true;
    } catch (Exception $e) {
      error_log("Failed to delete user: " . $e->getMessage());
      return false;
    }
  }
}
