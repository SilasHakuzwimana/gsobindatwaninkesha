<?php

namespace Models;

use Utils\HasUUID;
use Services\UUIDService;

class SchoolGallery extends BaseModel
{
  use HasUUID;

  public string $gallery_id;
  public ?string $image_path;
  public ?string $caption;
  public ?string $uploaded_by;

  public function __construct(array $data)
  {
    $this->generateId('gallery_id');
    $this->image_path = $data['image_path'] ?? null;
    $this->caption = $data['caption'] ?? null;
    $this->uploaded_by = $data['uploaded_by'] ?? null;
  }

  public function save(): bool
  {
    $sql = "INSERT INTO school_gallery (gallery_id, image_path, caption, uploaded_by)
                VALUES (?, ?, ?, ?)";
    $stmt = self::query($sql, [
      $this->gallery_id = UUIDService::generateBinary(),
      $this->image_path,
      $this->caption,
      $this->uploaded_by
    ]);
    return $stmt !== false;
  }
}
