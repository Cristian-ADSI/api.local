<?php

namespace Services\RestService\Helpers;

use Dotenv\Dotenv;
use Services\RestService\Interfaces\ConfigurationServiceInterface;

class ConfigurationService implements ConfigurationServiceInterface
{
  private $dotenv;
  public function __construct()
  {
    $this->dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
    $this->dotenv->load();
  }

  public function get(string $key, $default = null)
  {
    return $default;
  }

  public function getDatabaseConfig(): array
  {
    return [
      'host' => $_ENV['MySQL_DB_HOST'],
      'name' => $_ENV['MySQL_DB_NAME'],
      'user' => $_ENV['MySQL_DB_USER'],
      'pass' => $_ENV['MySQL_DB_PASS']
    ];
  }
}
