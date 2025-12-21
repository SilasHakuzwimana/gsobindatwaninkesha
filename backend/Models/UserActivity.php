<?php

namespace Models;

use PDO;
use Services\UUIDService;
use Utils\HasUUID;

class UserActivity extends BaseModel
{
  use HasUUID;

  protected static string $table = 'user_activities';

  public string $activity_id;
  public string $user_id;
  public string $activity_type;
  public ?string $activity_description;
  public ?string $ip_address;
  public ?string $user_agent;

  public function __construct(array $data = [])
  {
    if (!empty($data)) {
      $this->generateId('activity_id');
      $this->user_id = $data['user_id'];
      $this->activity_type = $data['activity_type'];
      $this->activity_description = $data['activity_description'] ?? null;
      $this->ip_address = $data['ip_address'] ?? null;
      $this->user_agent = $data['user_agent'] ?? null;
    }
  }

  /** 🔹 Save Activity */
  public function save(): bool
  {
    $sql = "INSERT INTO " . self::$table . " 
                    (activity_id, user_id, activity_type, activity_description, ip_address, user_agent)
                VALUES (?, ?, ?, ?, ?, ?)";

    return self::query($sql, [
      UUIDService::generateBinary(),
      hex2bin($this->user_id),
      $this->activity_type,
      $this->activity_description,
      $this->ip_address,
      $this->user_agent
    ]) !== false;
  }

  /** 🔹 Paginated + searchable + filterable + sortable list */
  public static function list(array $params): array
  {
    $db = self::db();

    $page     = $params['page'] ?? 1;
    $limit    = $params['limit'] ?? 10;
    $search   = $params['search'] ?? null;
    $type     = $params['type'] ?? null;
    $start    = $params['start'] ?? null;
    $end      = $params['end'] ?? null;
    $ip       = $params['ip'] ?? null;

    $sort_col = $params['sort_col'] ?? 'created_at';
    $sort_dir = strtoupper($params['sort_dir'] ?? 'DESC');

    $offset = ($page - 1) * $limit;

    $allowedCols = ["activity_type", "ip_address", "created_at"];
    if (!in_array($sort_col, $allowedCols)) {
      $sort_col = 'created_at';
    }

    $sort_dir = $sort_dir === 'ASC' ? 'ASC' : 'DESC';

    $sql = "SELECT 
                    HEX(activity_id) AS activity_id,
                    HEX(user_id) AS user_id,
                    activity_type,
                    activity_description,
                    ip_address,
                    user_agent,
                    created_at
                FROM " . self::$table . " 
                WHERE 1=1";

    $bindings = [];

    if ($search) {
      $sql .= " AND (
                        activity_type LIKE :s 
                        OR activity_description LIKE :s
                        OR user_agent LIKE :s
                    )";
      $bindings[':s'] = "%$search%";
    }

    if ($type) {
      $sql .= " AND activity_type = :t";
      $bindings[':t'] = $type;
    }

    if ($ip) {
      $sql .= " AND ip_address = :ip";
      $bindings[':ip'] = $ip;
    }

    if ($start && $end) {
      $sql .= " AND DATE(created_at) BETWEEN :start AND :end";
      $bindings[':start'] = $start;
      $bindings[':end'] = $end;
    }

    $sql .= " ORDER BY $sort_col $sort_dir LIMIT :offset, :limit";

    $stmt = $db->prepare($sql);

    foreach ($bindings as $k => $v) {
      $stmt->bindValue($k, $v);
    }

    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** 🔹 Single record */
  public static function show(string $uuid): ?array
  {
    $db = self::db();

    $sql = "SELECT 
                    HEX(activity_id) AS activity_id,
                    HEX(user_id) AS user_id,
                    activity_type,
                    activity_description,
                    ip_address,
                    user_agent,
                    created_at
                FROM " . self::$table . " 
                WHERE activity_id = UNHEX(?)";

    $stmt = $db->prepare($sql);
    $stmt->execute([$uuid]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  /** 🔹 Analytics for charts */
  public static function analytics(): array
  {
    $sql = "SELECT 
                    DATE(created_at) as day,
                    COUNT(*) as count
                FROM " . self::$table . " 
                GROUP BY DATE(created_at)
                ORDER BY day ASC";

    return self::fetchAll($sql);
  }

  /** 🔹 FIXED: fetchAll() implementation */
  public static function fetchAll(string $sql): array
  {
    $db = self::db();
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  //logging

  function logActivity(string $userId, string $type, ?string $description = null)
  {
    $activity = new \Models\UserActivity([
      'user_id' => $userId,
      'activity_type' => $type,
      'activity_description' => $description,
      'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
      'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
    ]);

    return $activity->save();
  }
}