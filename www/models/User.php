<?php

class User
{
  private int $id;
  private string $username;
  private string $birth;
  private int $role;
  private string $created_at;

  public function __construct(int $id, string $username, string $birth, int $role, string $created_at)
  {
    $this->id = $id;
    $this->username = $username;
    $this->birth = $birth;
    $this->role = $role;
    $this->created_at = $created_at;
  }


  public function getId()
  {
    return $this->id;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function getBirth()
  {
    return $this->birth;
  }

  public function getRole(): int
  {
    return (int) $this->role;
  }

  public function isAdmin(): bool
  {
    return $this->getRole() === 1;
  }

  public function getCreatedAt()
  {
    return $this->created_at;
  }

  public function getProfileImage(): string
  {
    $path = __DIR__ . '/../public/uploads/profiles/' . $this->id . '.webp';
    return file_exists($path) ? $this->id . '.webp' : 'default.webp';
  }
}
