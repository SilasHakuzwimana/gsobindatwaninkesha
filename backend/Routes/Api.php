<?php

namespace Routes;

use Controllers\AuthController;
use Controllers\UserController;
use Controllers\FileController;
use Controllers\DashboardController;
use Controllers\NewsletterController;
use Controllers\ContactController;
use Controllers\UserActivityController;
use Controllers\SupportController;
use Controllers\SubscribersController;
use Controllers\PathwayController;
use Controllers\MessagesController;
use Controllers\SchoolDocumentController;
use Controllers\GalleryController;
use Controllers\PathwayManagerController;
use Controllers\SchoolUpdateController;
use Middleware\AuthMiddleware;


use Config\Database;

class Api
{
  private array $routes = [];
  private \PDO $conn;

  /**
   * Request helper function
   */
  private function getRequestBody(): array
  {
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : [];
  }

  public function __construct()
  {
    // Use global PDO connection
    $this->conn = Database::getConnection();

    // =========================
    // GALLERY CONTROLLERS
    // =========================
    $schoolGalleryController  = new GalleryController();
    $alumniGalleryController  = new GalleryController();
    $extraGalleryController   = new GalleryController();
    $updatesGalleryController = new GalleryController();


    $this->routes = [
      // Public routes
      'POST /api/register'        => [AuthController::class, 'register'],
      'POST /api/login'           => [AuthController::class, 'login'],
      'POST /api/verify-otp'      => [AuthController::class, 'verifyOtp'],
      'POST /api/forgot-password' => [AuthController::class, 'forgotPassword'],
      'PUT /api/reset-password'   => [AuthController::class, 'resetPassword'],
      'POST /api/logout'          => fn() => (new AuthController())->logoutUser(),
      'POST /api/subscribe-newsletter' => fn() => (new NewsletterController())->subscribe(),
      'POST /api/contact'         => fn() => (new ContactController())->submit(),
      'POST /api/support-request' => fn() => (new SupportController())->submit(),

      // Protected / Admin routes

      'GET /api/dashboard/overview' => fn() => (new DashboardController())->overview(),


      'GET /api/users'            => fn() => (new UserController())->index(),
      'GET /api/users/:id'        => fn($id) => (new UserController())->getUserById($id),
      'POST /api/users'           => fn() => (new UserController())->createUser(),
      'PUT /api/users/:id'        => fn($id) => (new UserController())->updateUser($id),
      'DELETE /api/users/:id'     => fn($id) => (new UserController())->deleteUser($id),

      // Subscribers routes
      'GET /api/subscribers'      => fn() => (new SubscribersController($this->conn))->getSubscribers(),
      'POST /api/send-emails'     => fn() => (new SubscribersController($this->conn))->sendToSubscribers(),

      // Messages routes
      'GET /api/messages'      => fn() => (new MessagesController())->index(),
      'GET /api/all-messages' => fn() => (new MessagesController())->findAll(),
      'GET /api/messages/:id'  => fn($id) => (new MessagesController())->show($id),
      'POST /api/messages'     => fn() => (new MessagesController())->store(),
      'DELETE /api/messages/:id' => fn($id) => (new MessagesController())->deleteMessage($id),


      // User activities routes
      'GET /api/user-activities/list' => function () {
        header('Content-Type: application/json');
        echo json_encode((new UserActivityController())->list());
      },

      'GET /api/user-activities/show' => function () {
        header('Content-Type: application/json');
        echo json_encode((new UserActivityController())->show());
      },

      'GET /api/user-activities/analytics' => function () {
        header('Content-Type: application/json');
        echo json_encode((new UserActivityController())->analytics());
      },


      // File routes
      'POST /api/upload-file'     => fn() => (new FileController())->upload(),
      'GET /api/get-file-url'     => fn() => (new FileController())->getUrl(),
      'POST /api/delete-file'     => fn() => (new FileController())->delete(),


      // School Documents routes
      'GET /api/school-documents/all'      => fn() => (new SchoolDocumentController())->all(),
      'GET /api/school-documents/:id'    => fn($id) => (new SchoolDocumentController())->find($id),
      'POST /api/school-documents'        => fn() => (new SchoolDocumentController())->create(),
      'PUT /api/school-documents/:id'    => fn($id) => (new SchoolDocumentController())->update($id),
      'DELETE /api/school-documents/:id' => fn($id) => (new SchoolDocumentController())->delete($id),

      // =========================
      // SCHOOL GALLERY ROUTES
      // =========================
      'GET /api/gallery/school_gallery' => fn() => $schoolGalleryController->all(),
      'POST /api/gallery/school_gallery' => fn() => $schoolGalleryController->create('school_gallery'),
      'PUT /api/gallery/school_gallery/:id' => fn($id) => $schoolGalleryController->update('school_gallery', $id),
      'DELETE /api/gallery/school_gallery/:id' => fn($id) => $schoolGalleryController->delete('school_gallery', $id),


      // =========================
      // ALUMNI GALLERY ROUTES
      // =========================
      'GET /api/gallery/school_alumni_gallery' => fn() =>  $alumniGalleryController->all(),
      'POST /api/gallery/school_alumni_gallery' => fn() => $alumniGalleryController->create('school_alumni_gallery'),
      'PUT /api/gallery/school_alumni_gallery/:id' => fn($id) => $alumniGalleryController->update('school_alumni_gallery', $id),
      'DELETE /api/gallery/school_alumni_gallery/:id' => fn($id) => $alumniGalleryController->delete('school_alumni_gallery', $id),


      // =========================
      // EXTRA ACTIVITIES GALLERY ROUTES
      // =========================
      'GET /api/gallery/extra_curricular_activities_gallery' => fn() => $extraGalleryController->all(),
      'POST /api/gallery/extra_curricular_activities_gallery' => fn() => $extraGalleryController->create('extra_curricular_activities_gallery'),
      'PUT /api/gallery/extra_curricular_activities_gallery/:id' => fn($id) => $extraGalleryController->update('extra_curricular_activities_gallery', $id),
      'DELETE /api/gallery/extra_curricular_activities_gallery/:id' => fn($id) => $extraGalleryController->delete('extra_curricular_activities_gallery', $id),


      // =========================
      // UPDATES GALLERY ROUTES
      // =========================
      'GET /api/gallery/school_updates_gallery' => fn() => $updatesGalleryController->all(),
      'POST /api/gallery/school_updates_gallery' => fn() => $updatesGalleryController->create('school_updates_gallery'),
      'PUT /api/gallery/school_updates_gallery/:id' => fn($id) => $updatesGalleryController->update('school_updates_gallery', $id),
      'DELETE /api/gallery/school_updates_gallery/:id' => fn($id) => $updatesGalleryController->delete('school_updates_gallery', $id),


      //Old Pathway routes
      'GET /api/pathways'         => fn() => (new PathwayController())->index(),
      'GET /api/pathways/:id'     => fn($id) => (new PathwayController())->show($id),
      'POST /api/pathways'        => fn() => (new PathwayController())->store(),
      'PUT /api/pathways/:id'     => fn($id) => (new PathwayController())->update($id),
      'DELETE /api/pathways/:id' => fn($id) => (new PathwayController())->destroy($id),

      // Streams
      'GET /api/streams' => fn() => (new PathwayManagerController())->getAllStreams(),
      'GET /api/streams/:id' => fn($id) => (new PathwayManagerController())->getStreamsByPathway($id),
      'POST /api/streams' => fn() => (new PathwayManagerController())->createStream($_POST),

      'PUT /api/streams/:id' => fn($id) => (new PathwayManagerController())->updateStream(
        array_merge($this->getRequestBody(), ['stream_id' => $id])
      ),

      'DELETE /api/streams/:id' => fn($id) => (new PathwayManagerController())->deleteStream($id),

      'GET /api/pathways/:pathwayId/streams' => fn($pathwayId) => (new PathwayManagerController())->getStreamsByPathway($pathwayId),


      // Stream Subjects
      'GET /api/subjects' => fn() => (new PathwayManagerController())->getSubjects(),
      'GET /api/subjects/:id' => fn($id) => (new PathwayManagerController())->getSubjectsById($id),
      'GET /api/streams/:streamId/subject' => fn($streamId) => (new PathwayManagerController())->getSubjectsByStream($streamId),
      'POST /api/stream-subjects'            => fn() => (new PathwayManagerController())->createStreamSubject(),
      'PUT /api/stream-subjects/:id'         => fn($id) => (new PathwayManagerController())->updateStreamSubject($id),
      'DELETE /api/stream-subjects/:id'      => fn($id) => (new PathwayManagerController())->deleteStreamSubject($id),

      // =========================
      // SCHOOL UPDATES API
      // =========================
      'GET /api/school-updates'        => fn() => (new SchoolUpdateController())->getAll(),
      'GET /api/school-updates/:id'    => fn($id) => (new SchoolUpdateController())->getById($id),
      'POST /api/school-updates' => fn() => (new SchoolUpdateController())->handlePost(),
      'PUT /api/school-updates/:id'    => fn($id) => (new SchoolUpdateController())->update($id),
      'DELETE /api/school-updates/:id' => fn($id) => (new SchoolUpdateController())->delete($id),

    ];
  }



  public function run()
  {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      http_response_code(200);
      exit;
    }

    $method = $_SERVER['REQUEST_METHOD'];

    $path = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

    if ($path === '') $path = '/';

    foreach ($this->routes as $routeKey => $handler) {
      [$routeMethod, $routePath] = explode(' ', $routeKey, 2);
      if ($method !== $routeMethod) continue;

      $pattern = "#^" . preg_replace('/:\w+/', '([^/]+)', $routePath) . "$#";

      if (preg_match($pattern, $path, $matches)) {
        array_shift($matches);

        // Admin route protection
        $isAdmin =
          str_contains($routeKey, '/users') ||
          str_contains($routeKey, '/gallery') ||
          str_contains($routeKey, '/school-documents') ||
          str_contains($routeKey, '/pathways') ||
          str_contains($routeKey, '/streams') ||
          str_contains($routeKey, '/compulsory-subjects') ||
          str_contains($routeKey, '/stream-subjects');

        if ($isAdmin) {
          AuthMiddleware::requireAdmin();
        }

        if (is_array($handler)) {
          [$class, $func] = $handler;
          (new $class())->$func(...$matches);
        } else {
          $handler(...$matches);
        }
        return;
      }
    }

    // If route not found
    $this->handleError(404, 'Route not found');
  }

  private function handleError(int $code = 500, string $message = 'An error occurred')
  {
    // Ensure HTTP response code is set to a valid integer
    http_response_code($code ?? 500);
    $isApi = (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/api/') === 0);

    if ($isApi) {
      header('Content-Type: application/json');
      echo json_encode(['status' => 'error', 'code' => $code ?? 500, 'message' => $message ?? 'An error occurred']);
    } else {
      switch ($code ?? 500) {
        case 401:
          header('Location: /401');
          break;
        case 403:
          header('Location: /403');
          break;
        case 404:
          header('Location: /404');
          break;
        default:
          header('Location: /500');
          break;
      }
    }
    exit;
  }
}
