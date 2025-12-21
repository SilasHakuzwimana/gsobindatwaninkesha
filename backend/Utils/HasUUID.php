<?php

namespace Utils;

use Services\UUIDService;

trait HasUUID
{
  /**
   * Ensure the model property has a 32-char hex UUID (no dashes).
   * e.g. "7f7a3c152d344b6da08bcb03a8dc0912"
   */
  public function generateId(string $property = 'user_id'): void
  {
    if (!property_exists($this, $property)) {
      throw new \Exception("Property {$property} does not exist");
    }

    // Only generate if empty
    if (empty($this->{$property})) {
      // UUIDService::generateV4() returns dashed UUID; remove dashes
      $uuid = UUIDService::generateV4();
      $this->{$property} = str_replace('-', '', $uuid);
    }
  }

  /**
   * Return the 32-char hex (no dashes) used by SQL UNHEX(...)
   */
  public function getHexId(string $property = 'user_id'): string
  {
    if (!property_exists($this, $property)) {
      throw new \Exception("Property {$property} does not exist");
    }

    $value = $this->{$property} ?? '';
    if (empty($value)) {
      throw new \Exception("UUID property {$property} is empty");
    }

    // If value is binary 16-bytes, convert to hex
    if (strlen($value) === 16) {
      return bin2hex($value);
    }

    // If it has dashes remove them
    $hex = str_replace('-', '', $value);

    if (!ctype_xdigit($hex) || strlen($hex) !== 32) {
      throw new \Exception("Invalid UUID format in property {$property}");
    }

    return $hex;
  }

  /**
   * Convert binary(16) to readable dashed UUID string.
   */
  public function getReadableIdFromBinary(string $binary): string
  {
    return UUIDService::fromBinary($binary);
  }

  /**
   * Set the model id, accept dashed UUID or 32-char hex or binary.
   */
  public function setId(string $uuid, string $property = 'user_id'): void
  {
    if (!property_exists($this, $property)) {
      throw new \Exception("Property {$property} does not exist");
    }

    // binary
    if (strlen($uuid) === 16) {
      $this->{$property} = bin2hex($uuid);
      return;
    }

    // dashed or hex
    $hex = str_replace('-', '', $uuid);
    if (!ctype_xdigit($hex) || strlen($hex) !== 32) {
      throw new \Exception("Invalid UUID provided to setId()");
    }

    $this->{$property} = $hex;
  }
}