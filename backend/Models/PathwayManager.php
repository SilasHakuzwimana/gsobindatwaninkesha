<?php

namespace Models;

use PDO;
use Services\UUIDService;
use Middleware\AuthMiddleware;
use Config\Database;

class PathwayManager
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }


  // =========================
  // PATHWAYS
  // =========================
  public function getAllPathways(): array
  {
    $stmt = $this->db->query("SELECT * FROM pathways");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getPathwayById(string $pathwayId): array|false
  {
    $stmt = $this->db->prepare("SELECT * FROM pathways WHERE pathway_id = UNHEX(?)");
    $stmt->execute([$pathwayId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function createPathway(array $data): array
  {

    if (empty($data['pathway_name'])) {
      return ['status' => 'error', 'message' => 'Pathway name is required'];
    }
    $uuid = UUIDService::generateBinary();

    $stmt = $this->db->prepare("
            INSERT INTO pathways (pathway_id, pathway_name, description, created_by)
            VALUES (?, ?, ?, UNHEX(?))
        ");

    $success = $stmt->execute([
      $uuid,
      $data['pathway_name'],
      $data['description'] ?? null,
      $data['created_by'] ?? null
    ]);

    if ($success) {
      return [
        'status' => 'success',
        'message' => 'Pathway created',
        'id' => UUIDService::fromBinary($uuid)
      ];
    }

    return ['status' => 'error', 'message' => 'Failed to create pathway'];
  }

  public function updatePathway(array $data): array
  {
    $stmt = $this->db->prepare("
            UPDATE pathways
            SET pathway_name = ?, description = ?, updated_by = UNHEX(?), updated_at = CURRENT_TIMESTAMP
            WHERE pathway_id = UNHEX(?)
        ");

    $success = $stmt->execute([
      $data['pathway_name'],
      $data['description'] ?? null,
      $data['updated_by'] ?? null,
      $data['pathway_id']
    ]);

    return $success
      ? ['status' => 'success', 'message' => 'Pathway updated']
      : ['status' => 'error', 'message' => 'Failed to update pathway'];
  }

  public function deletePathway(string $pathwayId): array
  {
    $stmt = $this->db->prepare("DELETE FROM pathways WHERE pathway_id = UNHEX(?)");
    $success = $stmt->execute([$pathwayId]);

    return $success
      ? ['status' => 'success', 'message' => 'Pathway deleted']
      : ['status' => 'error', 'message' => 'Failed to delete pathway'];
  }

  // =========================
  // STREAMS
  // =========================

  /**
   * Fetch streams, optionally filtered by pathway.
   *
   * @param ?string $pathwayId UUID string of the pathway (optional)
   * @return array
   */
  public function getAllStreams(): array
  {
    // Fetch all streams if no pathwayId is provided
    $stmt = $this->db->query("SELECT * FROM pathways_streams");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getStreamsByPathway(string $pathwayId): array
  {
    $stmt = $this->db->prepare("SELECT * FROM pathways_streams WHERE pathway_id = UNHEX(?)");
    $stmt->execute([$pathwayId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function createStream(array $data): array
  {
    $uuid = UUIDService::generateBinary();

    $pathwayIdHex = str_replace('-', '', $data['pathway_id']);

    $stmt = $this->db->prepare("
            INSERT INTO pathways_streams (stream_id, pathway_id, stream_name, description, created_by)
            VALUES (?, UNHEX(?), ?, ?, UNHEX(?))
        ");

    $success = $stmt->execute([
      $uuid,
      $pathwayIdHex,
      $data['stream_name'],
      $data['description'] ?? null,
      $data['created_by'] ?? null
    ]);

    return $success
      ? ['status' => 'success', 'message' => 'Stream created', 'id' => UUIDService::fromBinary($uuid)]
      : ['status' => 'error', 'message' => 'Failed to create stream'];
  }

  // =========================
  // COMPULSORY SUBJECTS
  // =========================
  public function getCompulsorySubjectsByPathway(string $pathwayId): array
  {
    $stmt = $this->db->prepare("SELECT * FROM pathways_compulsory_subjects WHERE pathway_id = UNHEX(?)");
    $stmt->execute([$pathwayId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function createCompulsorySubject(array $data): array
  {
    $uuid = UUIDService::generateBinary();

    $stmt = $this->db->prepare("
            INSERT INTO pathways_compulsory_subjects
            (compulsory_subject_id, pathway_id, subject_name, subject_code, description, created_by)
            VALUES (?, UNHEX(?), ?, ?, ?, UNHEX(?))
        ");

    $success = $stmt->execute([
      $uuid,
      $data['pathway_id'],
      $data['subject_name'],
      $data['subject_code'] ?? null,
      $data['description'] ?? null,
      $data['created_by'] ?? null
    ]);

    return $success
      ? ['status' => 'success', 'message' => 'Compulsory subject created', 'id' => UUIDService::fromBinary($uuid)]
      : ['status' => 'error', 'message' => 'Failed to create compulsory subject'];
  }

  // =========================
  // STREAM SUBJECTS
  // =========================
  public function getSubjectsByStream(string $streamId): array
  {
    $stmt = $this->db->prepare("SELECT * FROM pathways_stream_subjects WHERE stream_id = UNHEX(?)");
    $stmt->execute([$streamId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function createStreamSubject(array $data): array
  {
    $uuid = UUIDService::generateBinary();

    $stmt = $this->db->prepare("
            INSERT INTO pathways_stream_subjects
            (subject_id, stream_id, subject_name, subject_code, description, created_by)
            VALUES (?, UNHEX(?), ?, ?, ?, UNHEX(?))
        ");

    $success = $stmt->execute([
      $uuid,
      $data['stream_id'],
      $data['subject_name'],
      $data['subject_code'] ?? null,
      $data['description'] ?? null,
      $data['created_by'] ?? null
    ]);

    return $success
      ? ['status' => 'success', 'message' => 'Stream subject created', 'id' => UUIDService::fromBinary($uuid)]
      : ['status' => 'error', 'message' => 'Failed to create stream subject'];
  }

  // =========================
  // UPDATE STREAM
  // =========================
  public function updateStream(array $data): array
  {
    $stmt = $this->db->prepare("
        UPDATE pathways_streams
        SET stream_name = ?, description = ?, updated_by = UNHEX(?), updated_at = CURRENT_TIMESTAMP
        WHERE stream_id = UNHEX(?)
    ");

    $success = $stmt->execute([
      $data['stream_name'],
      $data['description'] ?? null,
      $data['updated_by'] ?? null,
      $data['stream_id']
    ]);

    return $success
      ? ['status' => 'success', 'message' => 'Stream updated']
      : ['status' => 'error', 'message' => 'Failed to update stream'];
  }

  // =========================
  // DELETE STREAM
  // =========================
  public function deleteStream(string $streamId): array
  {
    $stmt = $this->db->prepare("DELETE FROM pathways_streams WHERE stream_id = UNHEX(?)");
    $success = $stmt->execute([$streamId]);
    return $success
      ? ['status' => 'success', 'message' => 'Stream deleted']
      : ['status' => 'error', 'message' => 'Failed to delete stream'];
  }

  // UPDATE COMPULSORY SUBJECT
  public function updateCompulsorySubject(array $data): array
  {
    $stmt = $this->db->prepare("
        UPDATE pathways_compulsory_subjects
        SET subject_name = ?, subject_code = ?, description = ?, updated_by = UNHEX(?), updated_at = CURRENT_TIMESTAMP
        WHERE compulsory_subject_id = UNHEX(?)
    ");

    $success = $stmt->execute([
      $data['subject_name'],
      $data['subject_code'] ?? null,
      $data['description'] ?? null,
      $data['updated_by'] ?? null,
      $data['compulsory_subject_id']
    ]);

    return $success
      ? ['status' => 'success', 'message' => 'Compulsory subject updated']
      : ['status' => 'error', 'message' => 'Failed to update compulsory subject'];
  }

  // DELETE COMPULSORY SUBJECT
  public function deleteCompulsorySubject(string $subjectId): array
  {
    $stmt = $this->db->prepare("DELETE FROM pathways_compulsory_subjects WHERE compulsory_subject_id = UNHEX(?)");
    $success = $stmt->execute([$subjectId]);

    return $success
      ? ['status' => 'success', 'message' => 'Compulsory subject deleted']
      : ['status' => 'error', 'message' => 'Failed to delete compulsory subject'];
  }

  // UPDATE STREAM SUBJECT
  public function updateStreamSubject(array $data): array
  {
    $stmt = $this->db->prepare("
        UPDATE pathways_stream_subjects
        SET subject_name = ?, subject_code = ?, description = ?, updated_by = UNHEX(?), updated_at = CURRENT_TIMESTAMP
        WHERE subject_id = UNHEX(?)
    ");

    $success = $stmt->execute([
      $data['subject_name'],
      $data['subject_code'] ?? null,
      $data['description'] ?? null,
      $data['updated_by'] ?? null,
      $data['subject_id']
    ]);

    return $success
      ? ['status' => 'success', 'message' => 'Stream subject updated']
      : ['status' => 'error', 'message' => 'Failed to update stream subject'];
  }

  // DELETE STREAM SUBJECT
  public function deleteStreamSubject(string $subjectId): array
  {
    $stmt = $this->db->prepare("DELETE FROM pathways_stream_subjects WHERE subject_id = UNHEX(?)");
    $success = $stmt->execute([$subjectId]);

    return $success
      ? ['status' => 'success', 'message' => 'Stream subject deleted']
      : ['status' => 'error', 'message' => 'Failed to delete stream subject'];
  }
}