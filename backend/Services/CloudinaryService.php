<?php

namespace Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Uploader;

require_once __DIR__ . '/../vendor/autoload.php';

class CloudinaryService
{
  private Cloudinary $cloudinary;

  public function __construct()
  {
    // Load credentials from env.php
    $config = require __DIR__ . '/../Config/env.php';

    $this->cloudinary = new Cloudinary([
      'cloud' => [
        'cloud_name' => $config['CLOUDINARY_CLOUD_NAME'],
        'api_key'    => $config['CLOUDINARY_API_KEY'],
        'api_secret' => $config['CLOUDINARY_API_SECRET'],
      ],
      'url' => [
        'secure' => true
      ]
    ]);
  }

  /**
   * Upload image
   * @param string $filePath Local file path
   * @param string $folder gsob_images or gsob_documents
   * @param string|null $publicId Optional public ID
   * @return array Uploaded file details
   */
  public function upload(string $filePath, string $folder, ?string $publicId = null): array
  {
    try {
      $options = [
        'folder' => $folder,
      ];

      if ($publicId) {
        $options['public_id'] = $publicId;
      }

      $result = $this->cloudinary->uploadApi()->upload($filePath, $options);
      return $result->getArrayCopy();
    } catch (ApiError $e) {
      throw new \Exception('Cloudinary Upload Error: ' . $e->getMessage());
    }
  }

  /**
   * Delete file by public ID
   * @param string $publicId
   * @return array
   */
  public function delete(string $publicId): array
  {
    try {
      return $this->cloudinary->uploadApi()->destroy($publicId)->getArrayCopy();
    } catch (ApiError $e) {
      throw new \Exception('Cloudinary Delete Error: ' . $e->getMessage());
    }
  }

  /**
   * Generate Cloudinary URL
   * @param string $publicId
   * @return string
   */
  public function getUrl(string $publicId): string
  {
    return $this->cloudinary->image($publicId)->toUrl();
  }
}
