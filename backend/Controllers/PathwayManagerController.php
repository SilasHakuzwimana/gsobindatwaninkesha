<?php

namespace Controllers;

use Models\PathwayManager;
use Middleware\AuthMiddleware;
use Services\UUIDService;

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
      'data' => $this->model->getStreams()
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
    if (empty($request)) {
      $request = json_decode(file_get_contents('php://input'), true);
    }

    if (empty($request['pathway_id']) || empty($request['stream_name'])) {
      echo json_encode(['status' => 'error', 'message' => 'pathway_id and stream_name are required']);
      return;
    }

    $currentUser = AuthMiddleware::requireAuth();
    echo json_encode($this->model->createStream($request, $currentUser));
  }

  public function updateStream(array $request = []): void
  {
    if (empty($request)) {
      $request = json_decode(file_get_contents('php://input'), true);
    }

    if (empty($request['stream_id']) || empty($request['stream_name'])) {
      echo json_encode(['status' => 'error', 'message' => 'stream_id and stream_name are required']);
      return;
    }

    $currentUser = AuthMiddleware::requireAuth();
    echo json_encode($this->model->updateStream($request, $currentUser));
  }

  public function deleteStream(string $streamId): void
  {
    if (empty($streamId)) {
      echo json_encode(['status' => 'error', 'message' => 'stream_id is required']);
      return;
    }

    echo json_encode($this->model->deleteStream($streamId));
  }

  // =========================
  // STREAM SUBJECTS
  // =========================
  public function getSubjects(): void
  {
    echo json_encode(['status' => 'success', 'data' => $this->model->getAllSubjects()]);
  }

  public function getSubjectsByStream(string $streamId): void
  {
    $streamBinary = UUIDService::toBinary($streamId);
    $subject = $this->model->getSubjectsByStream($streamBinary);
    var_dump($subject);
    // echo json_encode(['status' => 'success', 'data' => $this->model->getSubjectsByStream($streamId)]);
  }

  public function getSubjectsById(string $subjectId): void
  {
    $subject = $this->model->getSubjectById($subjectId);

    echo json_encode(
      $subject
        ? ['status' => 'success', 'data' => $subject]
        : ['status' => 'error', 'message' => 'Subject not found']
    );
  }


  public function createStreamSubject(): void
  {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true) ?? [];

    echo json_encode(
      $this->model->createStreamSubject($data)
    );
  }


  public function updateStreamSubject(string $subjectId): void
  {
    $request = json_decode(file_get_contents('php://input'), true) ?? [];

    //Inject subject_id from URL
    $request['subject_id'] = $subjectId;

    echo json_encode($this->model->updateStreamSubject($request));
  }

  public function deleteStreamSubject(string $subjectId): void
  {
    echo json_encode($this->model->deleteStreamSubject($subjectId));
  }
}
