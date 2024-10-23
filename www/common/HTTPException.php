<?php

class HTTPException extends Exception
{
  public function __construct(int $code, string $message)
  {
    parent::__construct($message, $code);

    $this->code = $code;
    $this->message = $message;
  }

  public function toJSON(): string
  {
    return json_encode(array("code" => $this->code, "message" => $this->message));
  }


  public static function sendException(int $code = 500, string $message = ""): void
  {
    $exception = new HTTPException($code, $message);

    http_response_code($code);
    header("Content-type: application/json; charset=utf-8");

    die($exception->toJSON());
  }
}
