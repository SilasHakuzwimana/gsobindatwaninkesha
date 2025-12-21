<?php

namespace Models;

use Utils\HasUUID;

class PathwayCompulsorySubject extends BaseModel
{
  use HasUUID;

  public string $compulsory_subject_id;
  public string $pathway_id;
  public string $subject_name;
  public ?string $subject_code;
  public ?string $description;

  public function __construct(array $data)
  {
    $this->generateId('compulsory_subject_id');
    $this->pathway_id = $data['pathway_id'];
    $this->subject_name = $data['subject_name'];
    $this->subject_code = $data['subject_code'] ?? null;
    $this->description = $data['description'] ?? null;
  }

  public function save(): bool
  {
    $sql = "INSERT INTO pathways_compulsory_subjects 
                (compulsory_subject_id, pathway_id, subject_name, subject_code, description)
                VALUES (?, ?, ?, ?, ?)";
    $stmt = self::query($sql, [
      $this->getBinaryId(),
      $this->pathway_id,
      $this->subject_name,
      $this->subject_code,
      $this->description
    ]);
    return $stmt !== false;
  }
}