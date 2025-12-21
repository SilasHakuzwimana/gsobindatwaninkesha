<?php
// ========================================================
// GLOBAL SETTINGS
// ========================================================
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('Africa/Kigali');

require_once __DIR__ . '/../backend/vendor/autoload.php';

use Routes\Api;
use Config\Database;

// ========================================================
// HEADERS FOR JSON / CORS
// ========================================================
header('Access-Control-Allow-Origin: *'); // ⚠️ Change * to your domain in production
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

// ========================================================
// INITIALIZE DATABASE
// ========================================================
Database::getConnection();

// ========================================================
// PARSE REQUEST URI
// ========================================================
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/');

$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$baseFolder = $scriptName !== '/' ? $scriptName : '';

if (str_starts_with($requestUri, $baseFolder)) {
  $requestUri = substr($requestUri, strlen($baseFolder));
}
if ($requestUri === '') {
  $requestUri = '/';
}

// ========================================================
// SERVE STATIC ASSETS
// ========================================================
$assetsDir = realpath(__DIR__ . '/../frontend/assets');

if (str_starts_with($requestUri, '/assets')) {
  $relativePath = substr($requestUri, strlen('/assets'));

  // Case-insensitive file finder
  function findFileCaseInsensitive(string $baseDir, array $pathParts)
  {
    $current = $baseDir;
    foreach ($pathParts as $part) {
      if (!is_dir($current)) return false;
      $found = false;
      foreach (scandir($current) as $item) {
        if (strcasecmp($item, $part) === 0) {
          $current .= DIRECTORY_SEPARATOR . $item;
          $found = true;
          break;
        }
      }
      if (!$found) return false;
    }
    return $current;
  }

  $pathParts = array_filter(explode('/', $relativePath));
  $assetFile = findFileCaseInsensitive($assetsDir, $pathParts);

  if ($assetFile && file_exists($assetFile) && str_starts_with($assetFile, $assetsDir)) {
    $ext = strtolower(pathinfo($assetFile, PATHINFO_EXTENSION));
    $mimeTypes = [
      'css'  => 'text/css',
      'js'   => 'application/javascript',
      'png'  => 'image/png',
      'jpg'  => 'image/jpeg',
      'jpeg' => 'image/jpeg',
      'gif'  => 'image/gif',
      'svg'  => 'image/svg+xml',
      'ico'  => 'image/x-icon',
      'webp' => 'image/webp'
    ];
    if (isset($mimeTypes[$ext])) {
      header("Content-Type: {$mimeTypes[$ext]}");
    }
    header("Cache-Control: max-age=86400");
    readfile($assetFile);
    exit;
  }

  http_response_code(404);
  echo "Asset not found";
  exit;
}

// ========================================================
// HANDLE BACKEND API ROUTES
// ========================================================
if (str_starts_with($requestUri, '/api')) {
  $router = new Api();
  $router->run();
  exit;
}

// ========================================================
// HANDLE DASHBOARD PARTIAL ROUTES (AJAX)
// ========================================================
$pagesDir = __DIR__ . '/../frontend/pages';

if (str_starts_with($requestUri, '/partials')) {
  $partialPath = substr($requestUri, strlen('/partials'));
  $partialFile = $pagesDir . '/admin/partials' . $partialPath;

  if (file_exists($partialFile . '.php')) {
    include $partialFile . '.php';
    exit;
  }

  http_response_code(404);
  header('Content-Type: application/json');
  echo json_encode([
    'error' => 'Partial not found',
    'path'  => $requestUri
  ]);
  exit;
}

// ========================================================
// GENERAL PAGE ROUTING (/login, /admin, etc.)
// ========================================================
if ($requestUri === '/') {
  $requestUri = '/index';
}

$pageFile = $pagesDir . $requestUri;

if (file_exists($pageFile . '.html')) {
  include $pageFile . '.html';
  exit;
}

if (file_exists($pageFile . '.php')) {
  include $pageFile . '.php';
  exit;
}

// ========================================================
// 404 ERROR HANDLING
// ========================================================
http_response_code(404);
$errorPage = $pagesDir . '/404.php';

if (file_exists($errorPage)) {
  include $errorPage;
} else {
  echo "<h1>404 Not Found</h1><p>The requested page could not be found.</p>";
}

// Log missing routes
error_log("404 - Route not found: $requestUri");