<?php
require_once __DIR__ . "/HTTPException.php";

class Validator
{

  public static function isRequired(mixed $value, string $fieldName, string $message = "is required"): void
  {
    if (empty($value)) {
      HTTPException::sendException(400, "{$fieldName} {$message}");
    }
  }

  /**
   * Check if the value is a string and optionally if it's within the specified min and max length.
   */
  public static function isString(
    mixed $value,
    string $fieldName,
    ?int $minLength = null,
    ?int $maxLength = null,
    string $message = "is not a valid string"
  ): void {
    $errors = [];

    Validator::isRequired($value, $fieldName);

    if (!is_string($value)) {
      $errors[] = "{$fieldName} {$message}";
    }

    if (is_string($value)) {
      $length = strlen($value);

      if ($minLength !== null && $length < $minLength) {
        $errors[] = "{$fieldName} must be at least {$minLength} characters long";
      }

      if ($maxLength !== null && $length > $maxLength) {
        $errors[] = "{$fieldName} must be no more than {$maxLength} characters long";
      }
    }

    if (!empty($errors)) {
      HTTPException::sendException(400, implode(", ", $errors));
    }
  }
  /**
   * Check if the value matches a valid password pattern. If not, throw an HTTPException with code 400.
   */
  public static function isPassword(mixed $value, string $fieldName, int $minLength = 8, string $message = "is not a valid password. Must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&.#^()+_-)"): void
  {
    Validator::isRequired($value, $fieldName);
    Validator::isString($value, $fieldName, $minLength);

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.#^()+_-])[A-Za-z\d@$!%*?&.#^()+_-]{8,}$/", $value)) {
      HTTPException::sendException(400, "{$fieldName} {$message}");
    }
  }

  /**
   * Check if the value is an array of strings. If not, throw an HTTPException with code 400.
   */
  public static function isStringArray(mixed $value, string $fieldName, ?int $minArrayLength = null, ?int $maxArrayLength = null, ?int $minLength = null, ?int $maxLength = null, string $message = "is not a string array"): void
  {
    Validator::isRequired($value, $fieldName);

    if (!is_array($value)) {
      HTTPException::sendException(400, "{$fieldName} {$message}");
    }

    $arrayLength = count($value);

    if ($minArrayLength !== null && $arrayLength < $minArrayLength) {
      HTTPException::sendException(400, "{$fieldName} must contain at least {$minArrayLength} items");
    }

    if ($maxArrayLength !== null && $arrayLength > $maxArrayLength) {
      HTTPException::sendException(400, "{$fieldName} must contain no more than {$maxArrayLength} items");
    }

    foreach ($value as $element) {
      Validator::isString($element, $fieldName, $minLength, $maxLength);
    }
  }


  /**
   * Check if the value is a valid integer. If not, throw an HTTPException with code 400.
   */
  public static function isInt(
    mixed $value,
    string $fieldName,
    ?int $min = null,
    ?int $max = null,
    string $message = "is not a valid integer"
  ): void {
    Validator::isRequired($value, $fieldName);
    if (!filter_var($value, FILTER_VALIDATE_INT)) {
      HTTPException::sendException(400, "{$fieldName} {$message}");
    }

    $intValue = (int)$value;

    if ($min !== null && $intValue < $min) {
      HTTPException::sendException(400, "{$fieldName} must be greater than or equal to {$min}");
    }

    if ($max !== null && $intValue > $max) {
      HTTPException::sendException(400, "{$fieldName} must be less than or equal to {$max}");
    }
  }

  /**
   * Check if the value is a valid date. If not, throw an HTTPException with code 400.
   */
  public static function isDate($date, string $fieldName): void
  {
    Validator::isRequired($date, $fieldName);
    if ($date instanceof DateTime) {
      return;
    }

    if (!is_string($date)) {
      throw new Exception("$fieldName must be a valid date string or DateTime object.");
    }

    $timestamp = strtotime($date);
    if ($timestamp === false) {
      throw new Exception("$fieldName must be a valid date.");
    }
  }

  public static function isProfileImage(?array $profileImage, string $fieldName): void
  {
    if (empty($profileImage)) {
      return;
    }


    if (!isset($profileImage['data']) || !isset($profileImage['type']) || !isset($profileImage['size'])) {
      HTTPException::sendException(400, "Invalid {$fieldName} data format");
    }

    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($profileImage['size'] > $maxSize) {
      HTTPException::sendException(400, "{$fieldName} must be smaller than 5MB");
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($profileImage['type'], $allowedTypes)) {
      HTTPException::sendException(400, "{$fieldName} must be JPEG, PNG or WebP image");
    }

    if (!preg_match('/^data:image\/(\w+);base64,/', $profileImage['data'])) {
      HTTPException::sendException(400, "Invalid {$fieldName} format");
    }
  }
}
