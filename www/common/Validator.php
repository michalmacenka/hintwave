<?php
require_once __DIR__ . "/HTTPException.php";

class Validator
{
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
  public static function isPassword(mixed $value, string $fieldName, int $minLength = 8, string $message = "is not a valid password. Must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character."): void
  {
    Validator::isString($value, $fieldName, $minLength);

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.#^()+])[A-Za-z\d@$!%*?&.#^()+]{8,}$/", $value)) {
      HTTPException::sendException(400, "{$fieldName} {$message}");
    }
  }

  /**
   * Check if the value is an array of strings. If not, throw an HTTPException with code 400.
   */
  public static function isStringArray(mixed $value, string $fieldName, ?int $minArrayLength = null, ?int $maxArrayLength = null, ?int $minLength = null, ?int $maxLength = null, string $message = "is not a string array"): void
  {
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
    if ($date instanceof DateTime) {
      return; // DateTime objekt je vždy validní datum
    }

    if (!is_string($date)) {
      throw new Exception("$fieldName must be a valid date string or DateTime object.");
    }

    $timestamp = strtotime($date);
    if ($timestamp === false) {
      throw new Exception("$fieldName must be a valid date.");
    }
  }

  /**
   * Check if the value is within a given range. If not, throw an HTTPException with code 400.
   */
  public static function isInRange(int $value, int $min, int $max, string $fieldName, string $message = "is not within the valid range"): void
  {
    if ($value < $min || $value > $max) {
      HTTPException::sendException(400, "{$fieldName} {$message}");
    }
  }
}
