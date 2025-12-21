<?php

namespace Models;

use Utils\HasUUID;

class PathwayStreamSubject extends BaseModel
{
  use HasUUID;

  public string $subject_id;
  public string $stream_id;
  public string $subject_name;
  public ?string $subject_code;
  public ?string $description;

  public function __construct(array $data)
  {
    $this->generateId('subject_id');
    $this->stream_id = $data['stream_id'];
    $this->subject_name = $data['subject_name'];
    $this->subject_code = $data['subject_code'] ?? null;
    $this->description = $data['description'] ?? null;
  }

  public function save(): bool
  {
    $sql = "INSERT INTO pathways_stream_subjects (subject_id, stream_id, subject_name, subject_code, description)
                VALUES (?, ?, ?, ?, ?)";
    $stmt = self::query($sql, [
      $this->getBinaryId(),
      $this->stream_id,
      $this->subject_name,
      $this->subject_code,
      $this->description
    ]);
    return $stmt !== false;
  }
}