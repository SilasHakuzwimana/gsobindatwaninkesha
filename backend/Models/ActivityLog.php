namespace Models;
<?php

use Models\BaseModel;

use PDO;

class ActivityLog extends BaseModel
{
  protected static string $table = 'activity_logs';

  public static function latest(int $limit = 5): array
  {
    $sql = "SELECT description, created_at
FROM activity_logs
ORDER BY created_at DESC
LIMIT ?";
    return self::query($sql, [$limit])
      ->fetchAll(PDO::FETCH_ASSOC);
  }
}
