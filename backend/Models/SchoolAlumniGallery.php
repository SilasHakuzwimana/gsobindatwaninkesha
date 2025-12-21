<?php

namespace Models;

use Utils\HasUUID;

class SchoolAlumniGallery extends BaseModel
{
  use HasUUID;

  public string $alumni_gallery_id;
  public ?string $image_path;
  public ?string $alumni_name;
  public ?string $year_graduated;
  public ?string $description;
  public ?string $uploaded_by;

  public function __construct(array $data)
  {
    $this->generateId('alumni_gallery_id');
    $this->image_path = $data['image_path'] ?? null;
    $this->alumni_name = $data['alumni_name'] ?? null;
    $this->year_graduated = $data['year_graduated'] ?? null;
    $this->description = $data['description'] ?? null;
    $this->uploaded_by = $data['uploaded_by'] ?? null;
  }

  public function save(): bool
  {
    $sql = "INSERT INTO school_alumni_gallery (alumni_gallery_id, image_path, alumni_name, year_graduated, description, uploaded_by)
                VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = self::query($sql, [
      $this->getBinaryId(),
      $this->image_path,
      $this->alumni_name,
      $this->year_graduated,
      $this->description,
      $this->uploaded_by
    ]);
    return $stmt !== false;
  }
}