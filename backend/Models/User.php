<?php

namespace Models;

use Utils\HasUUID;
use PDO;
use PDOException;
use Exception;

class User extends BaseModel
{
  use HasUUID;

  protected static string $table = 'users';
  protected static string $primaryKey = 'user_id';

  // Model properties (user_id stored as 32-char hex string)
  public string $user_id = '';
  public string $fullName = '';
  public string $email = '';
  public string $password = '';
  public string $role = 'student';
  public ?string $gender = null;
  public ?string $dob = null;
  public ?string $phone = null;
  public ?string $profile_photo = null;
  public string $status = 'active';
  public string $created_at;
  public string $updated_at;

  /**
   * Constructor
   * 
   * @param array $data
   * @param bool $fromDb Set true if data comes from DB (password already hashed)
   */
  public function __construct(array $data = [], bool $fromDb = false)
  {
    $this->fullName = $data['fullName'] ?? '';
    $this->email = $data['email'] ?? '';

    if (isset($data['password'])) {
      $this->password = $fromDb
        ? $data['password']                  // password from DB is already hashed
        : password_hash($data['password'], PASSWORD_BCRYPT); // hash new password
    }

    $this->role = $data['role'] ?? 'student';
    $this->gender = $data['gender'] ?? null;
    $this->dob = $data['dob'] ?? null;
    $this->phone = $data['phone'] ?? null;
    $this->profile_photo = $data['profile_photo'] ?? null;
    $this->status = $data['status'] ?? 'active';

    $now = new \DateTime('now', new \DateTimeZone('Africa/Kigali'));
    $this->created_at = $data['created_at'] ?? $now->format('Y-m-d H:i:s');
    $this->updated_at = $data['updated_at'] ?? $this->created_at;

    if (!empty($data['user_id'])) {
      $this->user_id = str_replace('-', '', $data['user_id']);
    }
  }

  /**
   * Total users count
   */
  public static function countAll(): int
  {
    $sql = "SELECT COUNT(*) FROM users";
    return (int) self::query($sql)->fetchColumn();
  }

  /**
   * Count users by role
   */
  public static function countByRole(string $role): int
  {
    $sql = "SELECT COUNT(*) FROM users WHERE role = ?";
    return (int) self::query($sql, [$role])->fetchColumn();
  }

  /**
   * Create new user (POST)
   */
  public static function create(array $data): array
  {
    $user = new self($data);
    $user->generateId('user_id');
    $user->fullName = $data['fullName'] ?? $user->fullName;
    $user->email = $data['email'] ?? $user->email;
    $user->password = isset($data['password']) ? password_hash($data['password'], PASSWORD_BCRYPT) : $user->password;
    $user->role = $data['role'] ?? $user->role;
    $user->gender = $data['gender'] ?? $user->gender;
    $user->dob = $data['dob'] ?? $user->dob;
    $user->phone = $data['phone'] ?? $user->phone;
    $user->profile_photo = $data['profile_photo'] ?? $user->profile_photo;
    $user->status = $data['status'] ?? $user->status;

    $sql = "INSERT INTO users 
          (user_id, fullName, email, password, role, gender, dob, phone, profile_photo, status, created_at, updated_at)
          VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
      $user->getHexId('user_id'), // hex string
      $user->fullName,
      $user->email,
      $user->password,
      $user->role,
      $user->gender,
      $user->dob,
      $user->phone,
      $user->profile_photo,
      $user->status,
      $user->created_at,
      $user->updated_at
    ];

    try {
      $stmt = self::query($sql, $params);

      if ($stmt === false) {
        throw new Exception("Failed to insert user");
      }

      // Return the inserted user
      return self::getById($user->getHexId('user_id'));
    } catch (PDOException $e) {
      // Handle duplicate email gracefully
      if ($e->getCode() == 23000) { // duplicate entry
        throw new Exception("Email already exists");
      }
      throw $e;
    }
  }


  /**
   * Find user by email
   */
  public static function findByEmail(string $email): ?self
  {
    $sql = "SELECT HEX(user_id) AS user_id, fullName, email, password, role, gender, dob, phone, profile_photo, status, created_at, updated_at
                FROM users WHERE email = ? LIMIT 1";

    $stmt = self::query($sql, [$email]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    return $data ? new self($data, true) : null; // <--- true = password from DB
  }

  /**
   * Get single user by ID
   */
  public static function getById(string $id): ?array
  {
    // Normalize the ID
    if (ctype_xdigit($id) && strlen($id) === 32) {
      // 32-char hex (no dashes) → convert to binary
      $userIdBinary = hex2bin($id);
    } elseif (ctype_xdigit(str_replace('-', '', $id)) && strlen(str_replace('-', '', $id)) === 32) {
      // 36-char UUID with dashes → remove dashes and convert
      $userIdBinary = hex2bin(str_replace('-', '', $id));
    } else {
      // Possibly already binary
      $userIdBinary = $id;
    }

    $sql = "SELECT 
                  HEX(user_id) AS user_id,
                  fullName,
                  email,
                  role,
                  gender,
                  dob,
                  phone,
                  profile_photo,
                  status,
                  created_at,
                  updated_at
              FROM users 
              WHERE user_id = ? 
              LIMIT 1";

    $stmt = self::query($sql, [$userIdBinary]);
    $user = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($user) {
      $timezone = new \DateTimeZone('Africa/Kigali');
      foreach (['created_at', 'updated_at'] as $field) {
        if (!empty($user[$field])) {
          $dt = new \DateTime($user[$field], $timezone);
          $user[$field] = $dt->format('Y-m-d H:i:s');
        }
      }
    }

    return $user ?: null;
  }

  /**
   * Get all users
   */
  public static function all(): array
  {
    $sql = "SELECT 
                HEX(user_id) AS user_id,
                fullName,
                email,
                role,
                gender,
                phone,
                profile_photo,
                status,
                created_at,
                updated_at
            FROM users
            ORDER BY created_at DESC";

    $stmt = self::query($sql);
    $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $timezone = new \DateTimeZone('Africa/Kigali');
    foreach ($users as &$user) {
      foreach (['created_at', 'updated_at'] as $field) {
        if (!empty($user[$field])) {
          $dt = new \DateTime($user[$field], $timezone);
          $user[$field] = $dt->format('Y-m-d H:i:s');
        }
      }
    }

    return $users;
  }


  /**
   * Update user fully
   */
  public static function updateFull(string $id, array $data): bool
  {
    $hex = str_replace('-', '', $id);

    $sql = "UPDATE users SET
                    fullName = ?,
                    email = ?,
                    password = ?,
                    role = ?,
                    gender = ?,
                    dob = ?,
                    phone = ?,
                    profile_photo = ?,
                    status = ?,
                    updated_at = ?
                WHERE user_id = UNHEX(?)";

    $password = isset($data['password']) ? password_hash($data['password'], PASSWORD_BCRYPT) : self::getPasswordById($hex);

    $params = [
      $data['fullName'] ?? '',
      $data['email'] ?? '',
      $password,
      $data['role'] ?? 'student',
      $data['gender'] ?? null,
      $data['dob'] ?? null,
      $data['phone'] ?? null,
      $data['profile_photo'] ?? null,
      $data['status'] ?? 'active',
      (new \DateTime('now', new \DateTimeZone('Africa/Kigali')))->format('Y-m-d H:i:s'),
      $hex
    ];

    $stmt = self::query($sql, $params);
    return $stmt->rowCount() > 0;
  }

  /**
   * Partial update
   */
  public static function patch(string $id, array $data): bool
  {
    $hex = str_replace('-', '', $id);

    $set = [];
    $params = [];
    $allowed = ['fullName', 'email', 'password', 'role', 'gender', 'dob', 'phone', 'profile_photo', 'status'];

    foreach ($allowed as $field) {
      if (array_key_exists($field, $data)) {
        $set[] = "$field = ?";
        $params[] = $field === 'password'
          ? password_hash($data['password'], PASSWORD_BCRYPT)
          : $data[$field];
      }
    }

    if (empty($set)) {
      throw new Exception("No valid fields provided for patch");
    }

    $set[] = "updated_at = ?";
    $params[] = (new \DateTime('now', new \DateTimeZone('Africa/Kigali')))->format('Y-m-d H:i:s');

    $params[] = $hex;
    $sql = "UPDATE users SET " . implode(', ', $set) . " WHERE user_id = UNHEX(?)";

    $stmt = self::query($sql, $params);
    return $stmt->rowCount() > 0;
  }

  /**
   * Delete user
   */
  public static function deleteById(string $id): bool
  {
    $hex = str_replace('-', '', $id);
    $sql = "DELETE FROM users WHERE user_id = UNHEX(?)";
    $stmt = self::query($sql, [$hex]);
    return $stmt->rowCount() > 0;
  }

  /**
   * Get password by id
   */
  private static function getPasswordById(string $hexId): string
  {
    $sql = "SELECT password FROM users WHERE user_id = UNHEX(?) LIMIT 1";
    $stmt = self::query($sql, [$hexId]);
    return $stmt->fetchColumn() ?: '';
  }
}
