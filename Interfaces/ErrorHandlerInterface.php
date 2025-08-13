<?php

namespace Services\RestService\Interfaces;

interface ErrorHandlerInterface
{
  public function logDatabaseError(\Exception $exception, string $context = ''): void;
  public function logAuthenticationError(\Exception $exception, string $context = ''): void;
  public function logValidationError(\Exception $exception, string $context = ''): void;
  public function logGeneralError(\Exception $exception, string $context = ''): void;
}
