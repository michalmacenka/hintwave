<?php

/**
 * Database connection and manipulation class
 * 
 * Handles all database operations using PDO for MySQL connections
 * 
 * @package HintWave
 * @author Your Name
 */
class Database
{
  /** @var string Database host */
  const HOST = 'mysql';

  /** @var string Database name */
  const DBNAME = 'hintwave';

  /** @var string Database user */
  const USER = 'root';

  /** @var string Database password */
  const PASS = 'root';

  /** @var PDO Database connection instance */
  private $conn;

  /**
   * Initialize database connection
   * 
   * @throws PDOException If connection fails
   */
  public function __construct()
  {
    $this->conn = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::DBNAME, self::USER, self::PASS, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $this->conn->query('SET NAMES utf8');
  }

  /**
   * Execute SELECT query
   * 
   * @param string $sql SQL query
   * @param array $params Parameters to bind
   * @return array Query results
   * @throws PDOException If query fails
   */
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

  private function execute(string $sql, array $params = []): PDOStatement
  {
    $stmt = $this->conn->prepare($sql);

    foreach ($params as $key => $value) {
      $type = PDO::PARAM_STR;
      if (is_int($value)) {
        $type = PDO::PARAM_INT;
      } elseif (is_bool($value)) {
        $type = PDO::PARAM_BOOL;
      }

      if (is_int($key)) {
        $stmt->bindValue($key + 1, $value, $type);
      } else {
        $stmt->bindValue($key, $value, $type);
      }
    }

    $stmt->execute();
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
