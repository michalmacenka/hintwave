<?php

class Database
{
  const HOST = 'mysql';
  const DBNAME = 'hintwave';
  const USER = 'root';
  const PASS = 'root';

  private $conn;

  public function __construct()
  {
    $this->conn = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::DBNAME, self::USER, self::PASS, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $this->conn->query('SET NAMES utf8');
  }

  public function select($sql, $params = [])
  {
    $stmt = $this->execute($sql, $params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insert($sql, $params)
  {
    $stmt = $this->execute($sql, $params);
    return $this->conn->lastInsertId();
  }

  public function delete($sql, $params)
  {
    $stmt = $this->execute($sql, $params);
  }

  public function update($sql, $params)
  {
    $stmt = $this->execute($sql, $params);
  }

  private function execute($sql, $params = [])
  {
    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
    return $stmt;
  }

  public function lastInsertId()
  {
    return $this->conn->lastInsertId();
  }

  public function beginTransaction()
  {
    return $this->conn->beginTransaction();
  }

  public function commit()
  {
    return $this->conn->commit();
  }

  public function rollBack()
  {
    return $this->conn->rollBack();
  }
}
