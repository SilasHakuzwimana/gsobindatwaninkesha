<?php

namespace Services;

class UUIDService
{
  /**
   * Generate a new UUID in **binary(16)** format for MySQL BINARY(16) storage
   *
   * @return string 16-byte binary UUID
   */
  public static function generateBinary(): string
  {
    $uuid = self::generateV4();
    return self::toBinary($uuid);
  }

  /**
   * Generate a random UUID v4 string
   *
   * @return string UUID v4 (36 chars, including hyphens)
   */
  public static function generateV4(): string
  {
    $data = random_bytes(16);

    // Set version to 0100 (v4)
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
  }

  /**
   * Convert UUID string to binary(16)
   *
   * @param string $uuid UUID string (with or without hyphens)
   * @return string 16-byte binary
   */
  public static function toBinary(string $uuid): string
  {
    $hex = str_replace('-', '', $uuid);
    if (strlen($hex) !== 32 || !ctype_xdigit($hex)) {
      throw new \Exception("Invalid UUID string: $uuid");
    }
    return hex2bin($hex);
  }

  /**
   * Convert binary(16) UUID to readable string
   *
   * @param string $binary 16-byte binary UUID
   * @return string UUID string (36 chars)
   */
  public static function fromBinary(string $binary): string
  {
    if (strlen($binary) !== 16) {
      throw new \Exception("Invalid binary UUID");
    }
    $hex = bin2hex($binary);
    return sprintf(
      '%s-%s-%s-%s-%s',
      substr($hex, 0, 8),
      substr($hex, 8, 4),
      substr($hex, 12, 4),
      substr($hex, 16, 4),
      substr($hex, 20, 12)
    );
  }
}