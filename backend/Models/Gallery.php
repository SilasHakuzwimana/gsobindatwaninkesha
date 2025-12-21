<?php

namespace Models;

use PDO;
use Services\UUIDService;

class Gallery extends BaseModel
{
  protected static string $table;
  protected static string $primaryKey;
  protected static array $tableColumns = [];

  public string $id;
  public ?string $title;
  public ?string $description;
  public ?string $file_path;
  public ?string $category;
  public ?string $uploaded_by;
  public ?string $updated_by;
  public ?string $uploaded_at;
  public ?string $updated_at;

  protected static array $tableSchemas = [
    'school_gallery' => [
      'id' => 'photo_id',
      'title' => 'title',
      'description' => 'description',
      'file_path' => 'image_path',
      'category' => 'category',
      'uploaded_by' => 'uploaded_by',
      'updated_by' => 'updated_by',
      'uploaded_at' => 'uploaded_at',
      'updated_at' => 'updated_at'
    ],
    'extra_curricular_activities_gallery' => [
      'id' => 'activity_photo_id',
      'title' => 'activity_name',
      'description' => 'description',
      'file_path' => 'image_path',
      'uploaded_by' => 'uploaded_by',
      'updated_by' => 'updated_by',
      'uploaded_at' => 'uploaded_at',
      'updated_at' => 'updated_at'
    ],
    'school_alumni_gallery' => [
      'id' => 'photo_id',
      'title' => 'title',
      'description' => 'description',
      'file_path' => 'image_path',
      'uploaded_by' => 'uploaded_by',
      'updated_by' => 'updated_by',
      'uploaded_at' => 'uploaded_at',
      'updated_at' => 'updated_at'
    ],
    'school_updates_gallery' => [
      'id' => 'update_id',
      'title' => 'title',
      'description' => 'description',
      'file_path' => 'image_path',
      'uploaded_by' => 'uploaded_by',
      'updated_by' => 'updated_by',
      'uploaded_at' => 'created_at',
      'updated_at' => 'updated_at'
    ],
  ];

  public function __construct(array $data = [], string $tableName = '')
  {
    if ($tableName) {
      self::$table = $tableName;
      self::$primaryKey = self::$tableSchemas[$tableName]['id'];
      self::$tableColumns = self::$tableSchemas[$tableName];
    }

    $this->id = $data['id'] ?? UUIDService::generateBinary();
    $this->title = $data['title'] ?? null;
    $this->description = $data['description'] ?? null;
    $this->file_path = $data['file_path'] ?? null;
    $this->category = $data['category'] ?? null;
    $this->uploaded_by = $data['uploaded_by'] ?? null;
    $this->updated_by = $data['updated_by'] ?? null;
    $this->uploaded_at = $data['uploaded_at'] ?? date('Y-m-d H:i:s');
    $this->updated_at = $data['updated_at'] ?? date('Y-m-d H:i:s');
  }

  public function save(): ?array
  {
    $exists = self::findById($this->id);
    $columns = self::$tableColumns;

    $data = [
      'id' => $this->id,
      'title' => $this->title,
      'description' => $this->description,
      'file_path' => $this->file_path,
      'category' => $this->category,
      'uploaded_by' => $this->uploaded_by,
      'updated_by' => $this->updated_by,
      'uploaded_at' => $this->uploaded_at,
      'updated_at' => $this->updated_at,
    ];

    $mapped = [];
    foreach ($columns as $key => $col) {
      $mapped[$key] = $data[$key] ?? null;
    }

    if ($exists) {
      $set = [];
      foreach ($columns as $key => $col) {
        if ($key !== 'id') $set[] = "$col = :$key";
      }
      $sql = "UPDATE " . self::$table . " SET " . implode(", ", $set) . " WHERE " . $columns['id'] . " = :id";
      $stmt = self::db()->prepare($sql);
      $stmt->execute($mapped);
    } else {
      $sql = "INSERT INTO " . self::$table . " (" . implode(", ", $columns) . ") VALUES (:" . implode(", :", array_keys($columns)) . ")";
      $stmt = self::db()->prepare($sql);
      $stmt->execute($mapped);
    }

    return self::findById($this->id);
  }

  public static function findById(string $binaryId): ?array
  {
    $columns = self::$tableColumns;
    $primary = $columns['id'];
    $stmt = self::db()->prepare("SELECT *, HEX($primary) as uuid FROM " . self::$table . " WHERE $primary = ?");
    $stmt->execute([$binaryId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  public function findAll()
  {
    $data = Gallery::allSections();
    return $this->jsonResponse(true, 'Fetched', 200, $data);
  }


  public static function delete(string $binaryId): bool
  {
    $columns = self::$tableColumns;
    $primary = $columns['id'];
    $stmt = self::db()->prepare("DELETE FROM " . self::$table . " WHERE $primary = ?");
    return $stmt->execute([$binaryId]);
  }



  //All table
  public static function allSections(): array
  {
    $sections = [
      'school_gallery' => 'School Gallery',
      'extra_curricular_activities_gallery' => 'Extra Curricular Activities',
      'school_alumni_gallery' => 'Alumni Gallery',
      'school_updates_gallery' => 'School Updates',
    ];

    $result = [];

    foreach ($sections as $table => $sectionTitle) {
      // Skip if schema not defined
      if (!isset(self::$tableSchemas[$table])) continue;

      // Initialize table columns and primary key
      self::$table = $table;
      self::$tableColumns = self::$tableSchemas[$table];
      self::$primaryKey = self::$tableColumns['id'];

      $columns = self::$tableColumns;
      $primaryKey = $columns['id'];

      // Fetch all rows
      $stmt = self::db()->query(
        "SELECT *, HEX($primaryKey) AS uuid FROM $table ORDER BY " .
          ($columns['uploaded_at'] ?? 'uploaded_at') . " DESC"
      );
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

      // Add section info
      foreach ($rows as &$row) {
        $row['section'] = $sectionTitle;
        $row['table'] = $table;
      }

      $result = array_merge($result, $rows);
    }

    return $result;
  }

  //JSON Responses helper

  private function jsonResponse(bool $status, string $message, int $code = 200, ?array $data = null)
  {
    http_response_code($code);
    header('Content-Type: application/json');

    // Clear any previous output
    if (ob_get_length()) ob_clean();

    echo json_encode([
      'status' => $status,
      'message' => $message,
      'data' => $data ?? []
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
  }
}