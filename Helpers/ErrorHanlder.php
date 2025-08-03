<?php

namespace Services\Rest\Helpers;

use Services\Rest\Interfaces\ErrorHandlerInterface;
use Services\Utils\Logger;

class ErrorHandler implements ErrorHandlerInterface
{
  public function logDatabaseError(\Exception $exception, string $context = ''): void
  {
    $message = $context ? "Database error in {$context}: " . $exception->getMessage() : $exception->getMessage();
    Logger::log("ERROR", $message, __FILE__, __LINE__);
  }

  public function logAuthenticationError(\Exception $exception, string $context = ''): void
  {
    $message = $context ? "Authentication error in {$context}: " . $exception->getMessage() : $exception->getMessage();
    Logger::log("ERROR", $message, __FILE__, __LINE__);
  }

  public function logValidationError(\Exception $exception, string $context = ''): void
  {
    $message = $context ? "Validation error in {$context}: " . $exception->getMessage() : $exception->getMessage();
    Logger::log("ERROR", $message, __FILE__, __LINE__);
  }

  public function logGeneralError(\Exception $exception, string $context = ''): void
  {
    $message = $context ? "General error in {$context}: " . $exception->getMessage() : $exception->getMessage();
    Logger::log("ERROR", $message, __FILE__, __LINE__);
  }
}
