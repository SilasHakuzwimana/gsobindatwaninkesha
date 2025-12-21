<?php

namespace Models;

use PDO;
use PDOException;
use Services\UUIDService;
use Config\Database;

abstract class BaseModel
{
  protected static ?PDO $db = null;
  protected static string $table;
  protected static string $primaryKey = 'id'; // Usually binary(16)

  /**
   * Inject a PDO connection into the model.
   */
  public static function setConnection(PDO $pdo): void
  {
    self::$db = $pdo;
  }

  /**
   * Prepare and execute a SQL query.
   */
  public static function query(string $sql, array $params = []): \PDOStatement
  {
    $stmt = self::db()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
  }

  /**
   * Retrieve all rows from the table.
   */
  public static function all(): array
  {
    try {
      $sql = "SELECT *, HEX(" . static::$primaryKey . ") AS uuid FROM " . static::$table;
      $stmt = self::query($sql);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $result ?: []; // always return an array
    } catch (PDOException $e) {
      // Log error if needed
      return []; // fallback to empty array instead of false
    }
  }


  /**
   * Find one record by UUID (binary form).
   */
  public static function findById(string $idBinary): ?array
  {
    try {
      $sql = "SELECT *, HEX(" . static::$primaryKey . ") AS uuid
                    FROM " . static::$table . " 
                    WHERE " . static::$primaryKey . " = ? LIMIT 1";
      $stmt = self::query($sql, [$idBinary]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result ?: null;
    } catch (PDOException $e) {
      throw new PDOException("findById() error: " . $e->getMessage());
    }
  }

  /**
   * Insert a new record (automatically generates a binary UUID).
   */
  public static function create(array $data): ?array
  {
    try {
      $id = UUIDService::generateBinary();
      $columns = array_keys($data);
      $placeholders = array_fill(0, count($columns), '?');

      $sql = sprintf(
        "INSERT INTO %s (%s, %s) VALUES (?, %s)",
        static::$table,
        static::$primaryKey,
        implode(', ', $columns),
        implode(', ', $placeholders)
      );

      $params = array_merge([$id], array_values($data));
      self::query($sql, $params);

      // Return inserted record
      return self::findById($id);
    } catch (PDOException $e) {
      throw new PDOException("create() error: " . $e->getMessage());
    }
  }

  /**
   * Update record by binary UUID.
   */
  public static function update(string $idBinary, array $data): bool
  {
    try {
      $setParts = [];
      foreach ($data as $column => $value) {
        $setParts[] = "$column = ?";
      }

      $sql = sprintf(
        "UPDATE %s SET %s WHERE %s = ?",
        static::$table,
        implode(', ', $setParts),
        static::$primaryKey
      );

      $params = array_merge(array_values($data), [$idBinary]);
      $stmt = self::query($sql, $params);
      return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
      throw new PDOException("update() error: " . $e->getMessage());
    }
  }

  /**
   * Delete a record by binary UUID.
   */
  public static function delete(string $idBinary): bool
  {
    try {
      $sql = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
      $stmt = self::query($sql, [$idBinary]);
      return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
      throw new PDOException("delete() error: " . $e->getMessage());
    }
  }

  // db helper function
  public static function db(): PDO
  {
    if (self::$db === null) {
      self::$db = Database::getConnection(); // or however you get PDO
    }
    return self::$db;
  }
}