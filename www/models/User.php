<?php

/**
 * User Model Class
 * 
 * Represents a user in the system
 * 
 * @package HintWave\Models
 */
class User
{
  /** @var int User ID */
  private int $id;

  /** @var string Username */
  private string $username;

  /** @var string Birth date */
  private string $birth;

  /** @var int User role (1 = admin, 0 = regular user) */
  private int $role;

  /** @var string Account creation date */
  private string $created_at;

  /**
   * Creates a new User instance
   * 
   * @param int $id User ID
   * @param string $username Username
   * @param string $birth Birth date
   * @param int $role User role
   * @param string $created_at Creation date
   */
  public function __construct(int $id, string $username, string $birth, int $role, string $created_at)
  {
    $this->id = $id;
    $this->username = $username;
    $this->birth = $birth;
    $this->role = $role;
    $this->created_at = $created_at;
  }

  /**
   * Get user ID
   * 
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Get username
   * 
   * @return string
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * Get birth date
   * 
   * @return string
   */
  public function getBirth()
  {
    return $this->birth;
  }

  /**
   * Get user role
   * 
   * @return int
   */
  public function getRole(): int
  {
    return (int) $this->role;
  }

  /**
   * Check if user is admin
   * 
   * @return bool
   */
  public function isAdmin(): bool
  {
    return $this->getRole() === 1;
  }

  /**
   * Get user creation date
   * 
   * @return string
   */
  public function getCreatedAt()
  {
    return $this->created_at;
  }

  /**
   * Get profile image path
   * 
   * @return string Relative path to profile image
   */
  public function getProfileImage(): string
  {
    $path = __DIR__ . '/../public/uploads/profiles/' . $this->id . '.webp';
    return file_exists($path) ? $this->id . '.webp' : 'default.webp';
  }
}
