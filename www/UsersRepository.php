<?php

class UsersRepository
{

  private $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function findAll()
  {
    $sql = 'SELECT * FROM users';
    $users = $this->db->select($sql);
    return $users;
  }
}
