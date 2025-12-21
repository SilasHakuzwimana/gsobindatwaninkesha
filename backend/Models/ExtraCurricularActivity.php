<?php

namespace Models;

use Utils\HasUUID;

class ExtraCurricularActivity extends BaseModel
{
  use HasUUID;

  public string $activity_id;
  public string $activity_name;
  public ?string $description;
  public ?string $image_path;
  public ?string $created_by;

  public function __construct(array $data)
  {
    $this->generateId('activity_id');
    $this->activity_name = $data['activity_name'];
    $this->description = $data['description'] ?? null;
    $this->image_path = $data['image_path'] ?? null;
    $this->created_by = $data['created_by'] ?? null;
  }

  public function save(): bool
  {
    $sql = "INSERT INTO extra_curricular_activities (activity_id, activity_name, description, image_path, created_by)
                VALUES (?, ?, ?, ?, ?)";
    $stmt = self::query($sql, [
      $this->getBinaryId(),
      $this->activity_name,
      $this->description,
      $this->image_path,
      $this->created_by
    ]);
    return $stmt !== false;
  }
}