<?php

namespace Models;

use Utils\HasUUID;

class PathwayStream extends BaseModel
{
  use HasUUID;

  public string $stream_id;
  public string $pathway_id;
  public string $stream_name;
  public ?string $description;

  public function __construct(array $data)
  {
    $this->generateId('stream_id');
    $this->pathway_id = $data['pathway_id'];
    $this->stream_name = $data['stream_name'];
    $this->description = $data['description'] ?? null;
  }

  public function save(): bool
  {
    $sql = "INSERT INTO pathways_streams (stream_id, pathway_id, stream_name, description)
                VALUES (?, ?, ?, ?)";
    $stmt = self::query($sql, [
      $this->generateId(),
      $this->pathway_id,
      $this->stream_name,
      $this->description
    ]);
    return $stmt !== false;
  }
}
