<?php

namespace Models;

use Utils\HasUUID;
use Services\UUIDService;

class Pathway extends BaseModel
{
  use HasUUID;

  protected static string $table = 'pathways';
  protected static string $primaryKey = 'pathway_id';

  public string $pathway_id;
  public string $pathway_name;
  public ?string $description;
  public ?string $created_at;
  public ?string $created_by;

  public function __construct(array $data)
  {
    $this->generateId('pathway_id');
    $this->pathway_name = $data['pathway_name'] ?? '';
    $this->description = $data['description'] ?? null;
    $this->created_at = $data['created_at'] ?? null;
    $this->created_by = $data['created_by'] ?? null;
  }

  /**
   * Get pathway by ID
   */
  public static function getById(string $idBinary): ?array
  {
    return self::findById($idBinary ?? '');
  }

  /**
   * Total pathways count
   */
  public static function countAll(): int
  {
    $sql = "SELECT COUNT(*) FROM pathways";
    return (int) self::query($sql)->fetchColumn();
  }

  public static function countStreams(): int
  {
    $stmt = self::db()->query("SELECT COUNT(*) FROM pathways_streams");
    return (int) $stmt->fetchColumn();
  }

  public static function countSubjects(): int
  {
    $stmt = self::db()->query("SELECT COUNT(*) FROM pathways_stream_subjects");
    return (int) $stmt->fetchColumn();
  }


  /**
   * Get all pathways
   */
  public static function getAll(): array
  {
    return self::all();
  }
  public function save(): bool
  {
    $sql = "INSERT INTO pathways (pathway_id, pathway_name, description, created_by)
                VALUES (?, ?, ?, ?)";
    $stmt = self::query($sql, [
      UUIDService::generateBinary(),
      $this->pathway_name,
      $this->description,
      $this->created_by
    ]);
    return $stmt !== false;
  }

  public static function updatePathway(string $idBinary, array $data): bool
  {
    $pathway = self::findById($idBinary ?? '');
    if (!$pathway) {
      return false;
    }

    try {
      $sql = "UPDATE pathways 
                SET pathway_name = ?, description = ?, created_by = ? 
                WHERE pathway_id = ?";

      $stmt = self::query($sql, [
        $data['pathway_name'] ?? null,
        $data['description'] ?? null,
        $data['created_by'] ?? null,
        $idBinary ?? ''
      ]);

      return $stmt !== false;
    } catch (\Exception $e) {
      return false;
    }
  }

  public static function deletePathway(string $idBinary): bool
  {
    try {
      $sql = "DELETE FROM pathways WHERE pathway_id = ?";
      $stmt = self::query($sql, [$idBinary ?? '']);
      return $stmt !== false;
    } catch (\Exception $e) {
      return false;
    }
  }

  public static function findPathwayById(string $idBinary): ?self
  {
    $sql = "SELECT HEX(pathway_id) AS pathway_id, pathway_name, description, created_by
                FROM pathways WHERE pathway_id = ? LIMIT 1";
    $stmt = self::query($sql, [$idBinary ?? '']);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($result) {
      return new self([
        'pathway_name' => $result['pathway_name'],
        'description' => $result['description'],
        'created_by' => $result['created_by']
      ]);
    }

    return null;
  }
}
