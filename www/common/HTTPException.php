<?php

/**
 * HTTP Exception Class
 * 
 * Handles HTTP errors and their JSON responses
 * 
 * @package HintWave\Common
 */
class HTTPException extends Exception
{
  /**
   * Constructor
   * 
   * @param int $code HTTP status code
   * @param string $message Error message
   */
  public function __construct(int $code, string $message)
  {
    parent::__construct($message, $code);

    $this->code = $code;
    $this->message = $message;
  }

  /**
   * Convert exception to JSON format
   * 
   * @return string JSON encoded error
   */
  public function toJSON(): string
  {
    return json_encode(array("code" => $this->code, "message" => $this->message));
  }

  /**
   * Send HTTP exception response and terminate execution
   * 
   * @param int $code HTTP status code
   * @param string $message Error message
   * @return void
   */
  public static function sendException(int $code = 500, string $message = ""): void
  {
    if (!headers_sent()) {
      http_response_code($code);
      header('Content-Type: application/json');
    }

    $exception = new HTTPException($code, $message);
    die($exception->toJSON());
  }
}
