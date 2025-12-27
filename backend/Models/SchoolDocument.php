<?php

namespace Models;

use PDO;
use Services\UUIDService;

class SchoolDocument extends BaseModel
{
  protected static string $table = 'school_documents';
  protected static string $primaryKey = 'document_id';

  public string $document_id;
  public string $title;
  public ?string $file_path;
  public ?string $uploaded_by;
  public ?string $category;
  public ?string $description;

  public function __construct(array $data = [])
  {
    $this->document_id = $data['document_id'] ?? UUIDService::generateBinary();
    $this->title = $data['title'] ?? '';
    $this->file_path = $data['file_path'] ?? null;
    $this->uploaded_by = $data['uploaded_by'] ?? null;
    $this->category = $data['category'] ?? 'other';
    $this->description = $data['description'] ?? null;
  }

  /**
   * Total school documents count
   */
  public static function countAll(): int
  {
    $sql = "SELECT COUNT(*) FROM school_documents";
    return (int) self::query($sql)->fetchColumn();
  }

  /**
   * Save or update record
   */
  public function save(): ?array
  {
    $exists = self::findById($this->document_id);
    $data = [
      'document_id' => $this->document_id,
      'title'       => $this->title,
      'file_path'   => $this->file_path,
      'uploaded_by' => $this->uploaded_by,
      'category'    => $this->category,
      'description' => $this->description,
      'uploaded_at' => date('Y-m-d H:i:s'),
      'updated_at'  => date('Y-m-d H:i:s')
    ];

    if ($exists) {
      self::update($this->document_id, $data);
      return self::findById($this->document_id);
    } else {
      // insert into DB and return the full row
      $stmt = self::db()->prepare("
            INSERT INTO " . self::$table . " 
            (document_id, title, file_path, uploaded_by, category, description, uploaded_at, updated_at)
            VALUES (:document_id, :title, :file_path, :uploaded_by, :category, :description, :uploaded_at, :updated_at)
        ");
      $stmt->execute($data);

      return self::findById($this->document_id);
    }
  }

  /**
   * Get all documents with uploader name
   */
  public static function allWithUploader(): array
  {
    $sql = "
            SELECT d.*, HEX(d.document_id) AS uuid,
                   u.full_name AS uploaded_by_name
            FROM " . self::$table . " d
            LEFT JOIN users u ON d.uploaded_by = u.user_id
            ORDER BY d.uploaded_at DESC
        ";
    $stmt = self::query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
  }
}
