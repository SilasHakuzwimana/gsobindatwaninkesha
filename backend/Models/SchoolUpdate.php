<?php

namespace Models;

use Utils\HasUUID;

class SchoolUpdate extends BaseModel
{
  use HasUUID;

  public string $update_id;
  public string $title;
  public string $content;
  public ?string $image_path;
  public ?string $posted_by;

  public function __construct(array $data)
  {
    $this->generateId('update_id');
    $this->title = $data['title'];
    $this->content = $data['content'];
    $this->image_path = $data['image_path'] ?? null;
    $this->posted_by = $data['posted_by'] ?? null;
  }

  public function save(): bool
  {
    $sql = "INSERT INTO school_updates (update_id, title, content, image_path, posted_by)
                VALUES (?, ?, ?, ?, ?)";
    $stmt = self::query($sql, [
      $this->getBinaryId(),
      $this->title,
      $this->content,
      $this->image_path,
      $this->posted_by
    ]);
    return $stmt !== false;
  }
}