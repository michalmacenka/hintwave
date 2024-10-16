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

  public function getRole()
  {
    return $this->role;
  }

  public function getCreatedAt()
  {
    return $this->created_at;
  }
}
