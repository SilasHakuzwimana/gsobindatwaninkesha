<?php

namespace Models;

use Utils\HasUUID;
use Services\UUIDService;

class SchoolUpdate extends BaseModel
{
  use HasUUID;

  //Set table name
  protected static string $table = 'school_updates_gallery';
  protected static string $primaryKey = 'update_id';

  public string $update_id;
  public ?string $title;
  public ?string $description;
  public ?string $image_path;
  public ?string $posted_by;
  public ?string $updated_by;
  public ?string $created_at;
  public ?string $updated_at;


  public function __construct(array $data = [])
  {
    $this->generateId('update_id');
    $this->title = $data['title'] ?? null;
    $this->description = $data['description'] ?? null;
    $this->image_path = $data['image_path'] ?? null;
    $this->posted_by = $data['posted_by'] ?? null;
    $this->updated_by = $data['updated_by'] ?? $this->posted_by;

    date_default_timezone_set('Africa/Kigali');
    $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
    $this->updated_at = $data['updated_at'] ?? $this->created_at;
  }

  public function generateId(string $property = 'update_id'): void
  {
    if (!property_exists($this, $property)) {
      throw new \Exception("Property {$property} does not exist");
    }

    // Only generate if empty
    if (empty($this->{$property})) {
      // UUIDService::generateV4() returns dashed UUID; remove dashes
      $uuid = UUIDService::generateV4();
      $this->{$property} = str_replace('-', '', $uuid);
    }
  }

  /**
   * Get all updates
   */
  public static function getAllUpdates(): array
  {
    return self::all();
  }

  public function save(): bool
  {
    $sql = "INSERT INTO school_updates_gallery 
    (update_id, title, description, image_path, uploaded_by, updated_by, created_at, updated_at)
    VALUES (UNHEX(?), ?, ?, ?, UNHEX(?), UNHEX(?), ?, ?)";

    $stmt = self::query($sql, [
      $this->update_id,
      $this->title,
      $this->description,
      $this->image_path,
      $this->posted_by,
      $this->updated_by,
      $this->created_at,
      $this->updated_at
    ]);

    return $stmt !== false;
  }
}