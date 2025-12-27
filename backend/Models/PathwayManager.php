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

    $currentUser = AuthMiddleware::requireAuth();
    $userId = $currentUser['user_id'];
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
  public function getStreams(): array
  {
    $stmt = $this->db->query("
        SELECT 
            HEX(s.stream_id) AS stream_id,
            HEX(s.pathway_id) AS pathway_id,
            s.stream_name,
            s.description,
            HEX(s.created_by) AS created_by,
            HEX(s.updated_by) AS updated_by,
            s.created_at,
            s.updated_at,
            p.pathway_name
        FROM pathways_streams s
        JOIN pathways p ON s.pathway_id = p.pathway_id
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public function getStreamsByPathway(string $pathwayId): array
  {
    $stmt = $this->db->prepare("
        SELECT 
            HEX(s.stream_id) AS stream_id,
            HEX(s.pathway_id) AS pathway_id,
            s.stream_name,
            s.description,
            HEX(s.created_by) AS created_by,
            HEX(s.updated_by) AS updated_by,
            s.created_at,
            s.updated_at,
            p.pathway_name
        FROM pathways_streams s
        JOIN pathways p ON s.pathway_id = p.pathway_id
        WHERE s.pathway_id = UNHEX(?)
    ");
    $stmt->execute([$pathwayId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function createStream(array $data, array $currentUser): array
  {
    $uuid = UUIDService::generateBinary();

    // Convert logged-in user to binary
    $userId = $currentUser['user_id'] ?? $currentUser['sub'] ?? null;
    if (!$userId) return ['status' => 'error', 'message' => 'Logged in user not found'];

    $userBinary = (strlen($userId) === 36)
      ? UUIDService::toBinary($userId)
      : (strlen($userId) === 32 ? hex2bin($userId) : $userId);

    $pathwayIdHex = str_replace('-', '', $data['pathway_id']);

    $stmt = $this->db->prepare("
        INSERT INTO pathways_streams (stream_id, pathway_id, stream_name, description, created_by)
        VALUES (?, UNHEX(?), ?, ?, ?)
    ");

    $success = $stmt->execute([
      $uuid,
      $pathwayIdHex,
      $data['stream_name'],
      $data['description'] ?? null,
      $userBinary
    ]);

    return $success
      ? ['status' => 'success', 'message' => 'Stream created', 'id' => UUIDService::fromBinary($uuid)]
      : ['status' => 'error', 'message' => 'Failed to create stream'];
  }

  public function updateStream(array $data, array $currentUser): array
  {
    $userId = $currentUser['user_id'] ?? $currentUser['sub'] ?? null;
    if (!$userId) return ['status' => 'error', 'message' => 'Logged in user not found'];

    $userBinary = (strlen($userId) === 36)
      ? UUIDService::toBinary($userId)
      : (strlen($userId) === 32 ? hex2bin($userId) : $userId);

    $stmt = $this->db->prepare("
        UPDATE pathways_streams
        SET stream_name = ?,
            description = ?,
            pathway_id = UNHEX(REPLACE(?, '-', '')),
            updated_by = ?,
            updated_at = CURRENT_TIMESTAMP
        WHERE stream_id = UNHEX(REPLACE(?, '-', ''))
    ");


    $success = $stmt->execute([
      $data['stream_name'],
      $data['description'] ?? null,
      $data['pathway_id'],
      $userBinary,
      $data['stream_id']
    ]);

    return $success
      ? ['status' => 'success', 'message' => 'Stream updated']
      : ['status' => 'error', 'message' => 'Failed to update stream'];
  }

  public function deleteStream(string $streamId): array
  {
    $stmt = $this->db->prepare("DELETE FROM pathways_streams WHERE stream_id = UNHEX(?)");
    $success = $stmt->execute([$streamId]);
    return $success
      ? ['status' => 'success', 'message' => 'Stream deleted']
      : ['status' => 'error', 'message' => 'Failed to delete stream'];
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

  public function getAllSubjects(): array
  {
    $stmt = $this->db->prepare("
        SELECT 
            HEX(pss.subject_id) AS subject_id,
            HEX(pss.stream_id) AS stream_id,
            ps.stream_name,
            p.pathway_name,
            pss.subject_name,
            pss.subject_code,
            pss.description,
            pss.created_by,
            pss.updated_by,
            pss.created_at,
            pss.updated_at
        FROM pathways_stream_subjects pss
        JOIN pathways_streams ps ON pss.stream_id = ps.stream_id
        JOIN pathways p ON ps.pathway_id = p.pathway_id
    ");

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getSubjectsByStream(string $streamId): array
  {
    $stmt = $this->db->prepare("
        SELECT 
            HEX(pss.subject_id) AS subject_id,
            HEX(pss.stream_id) AS stream_id,
            ps.stream_name,
            p.pathway_name,
            pss.subject_name,
            pss.subject_code,
            pss.description,
            HEX(pss.created_by) AS created_by,
            HEX(pss.updated_by) AS updated_by,
            pss.created_at,
            pss.updated_at
        FROM pathways_stream_subjects pss
        JOIN pathways_streams ps ON pss.stream_id = ps.stream_id
        JOIN pathways p ON ps.pathway_id = p.pathway_id
        WHERE pss.stream_id = UNHEX(?)
    ");

    $stmt->execute([$streamId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getSubjectById(string $subjectId): array|false
  {
    $subjectIdHex = str_replace('-', '', $subjectId);

    $stmt = $this->db->prepare("
        SELECT 
            HEX(pss.subject_id) AS subject_id,
            HEX(pss.stream_id) AS stream_id,
            HEX(ps.pathway_id) AS pathway_id,
            ps.stream_name,
            p.pathway_name,
            pss.subject_name,
            pss.subject_code,
            pss.description,
            pss.created_at,
            pss.updated_at
        FROM pathways_stream_subjects pss
        JOIN pathways_streams ps ON pss.stream_id = ps.stream_id
        JOIN pathways p ON ps.pathway_id = p.pathway_id
        WHERE pss.subject_id = UNHEX(?)
        LIMIT 1
    ");

    $stmt->execute([$subjectIdHex]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function createStreamSubject(array $data, array $currentUser = []): array
  {
    // error_log('createStreamSubject received data: ' . json_encode($data));


    // Ensure required fields exist
    if (empty($data['stream_id']) || empty($data['subject_name'])) {
      return ['status' => 'error', 'message' => 'stream_id and subject_name are required'];
    }

    // Convert stream_id (already 32-char hex) to binary
    $streamBinary = UUIDService::toBinary($data['stream_id']);

    // Convert created_by to binary if available
    $createdBy = $currentUser['user_id'] ?? $data['created_by'] ?? null;
    if ($createdBy) {
      $createdBy = hex2bin($createdBy); // no hyphen removal needed
    }

    $uuid = UUIDService::generateBinary();

    $stmt = $this->db->prepare("
        INSERT INTO pathways_stream_subjects
        (subject_id, stream_id, subject_name, subject_code, description, created_by)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $success = $stmt->execute([
      $uuid,
      $streamBinary,
      $data['subject_name'],
      $data['subject_code'] ?? null,
      $data['description'] ?? null,
      $createdBy ?? null
    ]);

    if (!$success) {
      $errorInfo = $stmt->errorInfo();
      error_log('createStreamSubject SQL error: ' . print_r($errorInfo, true));
    }

    return $success
      ? ['status' => 'success', 'message' => 'Stream subject created', 'id' => UUIDService::fromBinary($uuid)]
      : ['status' => 'error', 'message' => 'Failed to create stream subject'];
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
    // 🔐 Required fields (DB-level requirements)
    if (empty($data['subject_id']) || empty($data['subject_name'])) {
      return [
        'status' => 'error',
        'message' => 'subject_id and subject_name are required'
      ];
    }

    // Normalize UUIDs (remove hyphens)
    $subjectIdHex = str_replace('-', '', $data['subject_id']);

    $updatedByHex = null;
    if (!empty($data['updated_by'])) {
      $updatedByHex = str_replace('-', '', $data['updated_by']);
    }

    $stmt = $this->db->prepare("
    UPDATE pathways_stream_subjects
    SET 
      stream_id=UNHEX(?),
      subject_name = ?,
      subject_code = ?,
      description = ?,
      updated_by = UNHEX(?),
      updated_at = CURRENT_TIMESTAMP
    WHERE subject_id = UNHEX(?)
  ");

    $success = $stmt->execute([
      $data['stream_id'],
      $data['subject_name'],
      $data['subject_code'] ?? null,
      $data['description'] ?? null,
      $updatedByHex,
      $subjectIdHex
    ]);

    return $success
      ? ['status' => 'success', 'message' => 'Stream subject updated']
      : ['status' => 'error', 'message' => 'Failed to update stream subject'];
  }


  // DELETE STREAM SUBJECT
  public function deleteStreamSubject(string $subjectId): array
  {
    if (empty($subjectId)) {
      return [
        'status' => 'error',
        'message' => 'subject_id is required'
      ];
    }

    $subjectIdHex = str_replace('-', '', $subjectId);

    $stmt = $this->db->prepare("
    DELETE FROM pathways_stream_subjects
    WHERE subject_id = UNHEX(?)
  ");

    $success = $stmt->execute([$subjectIdHex]);

    return $success
      ? ['status' => 'success', 'message' => 'Stream subject deleted']
      : ['status' => 'error', 'message' => 'Failed to delete stream subject'];
  }
}
