<?php

namespace Controllers;

use Models\PathwayManager;

class PathwayManagerController
{
  private PathwayManager $model;

  public function __construct()
  {
    $this->model = new PathwayManager();
    header('Content-Type: application/json'); // Ensure all responses are JSON
  }

  // =========================
  // PATHWAYS
  // =========================
  public function getAllPathways(): void
  {
    echo json_encode(['status' => 'success', 'data' => $this->model->getAllPathways()]);
  }


  public function getPathway(string $id): void
  {
    $data = $this->model->getPathwayById($id);
    echo json_encode($data
      ? ['status' => 'success', 'data' => $data]
      : ['status' => 'error', 'message' => 'Pathway not found']);
  }

  public function createPathway(array $request): void
  {
    echo json_encode($this->model->createPathway($request));
  }

  public function updatePathway(array $request): void
  {
    echo json_encode($this->model->updatePathway($request));
  }

  public function deletePathway(string $id): void
  {
    echo json_encode($this->model->deletePathway($id));
  }

  // =========================
  // STREAMS
  // =========================


  public function getAllStreams(): void
  {
    echo json_encode([
      'status' => 'success',
      'data' => $this->model->getAllStreams()
    ]);
  }


  public function getStreamsByPathway(string $pathwayId): void
  {
    echo json_encode([
      'status' => 'success',
      'data' => $this->model->getStreamsByPathway($pathwayId)
    ]);
  }

  public function createStream(array $request = []): void
  {
    // Decode JSON body if $request is empty
    if (empty($request)) {
      $request = json_decode(file_get_contents('php://input'), true);
    }

    // Validate required fields
    if (empty($request['pathway_id']) || empty($request['stream_name'])) {
      echo json_encode([
        'status' => 'error',
        'message' => 'pathway_id and stream_name are required'
      ]);
      return;
    }

    // Set created_by if missing
    $request['created_by'] = $request['created_by'] ?? '00000000-0000-0000-0000-000000000000';

    echo json_encode($this->model->createStream($request));
  }

  public function updateStream(array $request = []): void
  {
    if (empty($request)) {
      $request = json_decode(file_get_contents('php://input'), true);
    }

    if (empty($request['stream_id']) || empty($request['stream_name'])) {
      echo json_encode([
        'status' => 'error',
        'message' => 'stream_id and stream_name are required'
      ]);
      return;
    }

    // Set updated_by if missing
    $request['updated_by'] = $request['updated_by'] ?? '00000000-0000-0000-0000-000000000000';

    echo json_encode($this->model->updateStream($request));
  }

  public function deleteStream(string $streamId): void
  {
    if (empty($streamId)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'stream_id is required'
      ]);
      return;
    }

    echo json_encode($this->model->deleteStream($streamId));
  }

  // =========================
  // COMPULSORY SUBJECTS
  // =========================
  public function getCompulsorySubjectsByPathway(string $pathwayId): void
  {
    echo json_encode(['status' => 'success', 'data' => $this->model->getCompulsorySubjectsByPathway($pathwayId)]);
  }

  public function createCompulsorySubject(array $request): void
  {
    echo json_encode($this->model->createCompulsorySubject($request));
  }

  public function updateCompulsorySubject(array $request): void
  {
    echo json_encode($this->model->updateCompulsorySubject($request));
  }

  public function deleteCompulsorySubject(string $subjectId): void
  {
    echo json_encode($this->model->deleteCompulsorySubject($subjectId));
  }

  // =========================
  // STREAM SUBJECTS
  // =========================
  public function getSubjectsByStream(string $streamId): void
  {
    echo json_encode(['status' => 'success', 'data' => $this->model->getSubjectsByStream($streamId)]);
  }

  public function createStreamSubject(array $request): void
  {
    echo json_encode($this->model->createStreamSubject($request));
  }

  public function updateStreamSubject(array $request): void
  {
    echo json_encode($this->model->updateStreamSubject($request));
  }

  public function deleteStreamSubject(string $subjectId): void
  {
    echo json_encode($this->model->deleteStreamSubject($subjectId));
  }
}