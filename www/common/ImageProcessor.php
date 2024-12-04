<?php

/**
 * Image Processor Class
 * 
 * Handles image processing operations including resizing and format conversion
 * 
 * @package HintWave\Common
 */
class ImageProcessor
{
  /** @var int Maximum image width */
  private const MAX_WIDTH = 150;

  /** @var int Maximum image height */
  private const MAX_HEIGHT = 150;

  /** @var string Upload directory path */
  private const UPLOAD_DIR = __DIR__ . '/../public/uploads/profiles/';

  /** @var int WebP quality setting (0-100) */
  private const WEBP_QUALITY = 90;

  /**
   * Process and save profile image
   * 
   * @param string $tmpPath Temporary file path
   * @param int $userId User ID
   * @throws Exception If image processing fails
   * @return void
   */
  public static function processProfileImage(string $tmpPath, int $userId): void
  {
    if (!file_exists(self::UPLOAD_DIR)) {
      mkdir(self::UPLOAD_DIR, 0777, true);
    }

    $imageInfo = getimagesize($tmpPath);
    if (!$imageInfo) {
      throw new Exception('Invalid image file');
    }

    [$width, $height, $type] = $imageInfo;

    $sourceImage = match ($type) {
      IMAGETYPE_JPEG => imagecreatefromjpeg($tmpPath),
      IMAGETYPE_PNG => imagecreatefrompng($tmpPath),
      IMAGETYPE_WEBP => imagecreatefromwebp($tmpPath),
      default => throw new Exception('Unsupported image format. Please use JPEG, PNG or WebP.')
    };

    if (!$sourceImage) {
      throw new Exception('Failed to create image from uploaded file');
    }

    $ratio = min(self::MAX_WIDTH / $width, self::MAX_HEIGHT / $height);
    $newWidth = (int)($width * $ratio);
    $newHeight = (int)($height * $ratio);

    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    imagepalettetotruecolor($newImage);
    imagealphablending($newImage, false);
    imagesavealpha($newImage, true);

    imagecopyresampled(
      $newImage,
      $sourceImage,
      0,
      0,
      0,
      0,
      $newWidth,
      $newHeight,
      $width,
      $height
    );

    $filename = $userId . '.webp';
    $path = self::UPLOAD_DIR . $filename;

    if (!imagewebp($newImage, $path, self::WEBP_QUALITY)) {
      throw new Exception('Failed to save image');
    }

    imagedestroy($sourceImage);
    imagedestroy($newImage);
  }
}
