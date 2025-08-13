<?php

namespace Services\RestService\Models;

use Services\Config\ConnectionInterface;
use Services\RestService\Interfaces\ErrorHandlerInterface;
use PDO;
use PDOException;
use PDOStatement;
use Services\RestService\Interfaces\ModelValidatorInterface;

/**
 * Model for handling POST insertions into the database.
 */
class POSTModel
{
  /** @var ConnectionInterface */
  private ConnectionInterface $connection;

  /** @var ErrorHandlerInterface */
  private ErrorHandlerInterface $errorHandler;

  /** @var array */
  private array $requestData;

  /** @var array */
  private array $columns;

  /** @var array */
  private array $values;

  /** @var string */
  private string $table;

  /** @var string */
  private string $query;

  /** @var PDO */
  private ?PDO $database;

  /** @var PDOStatement */
  private ?PDOStatement $stmt;

  /** @var ModelValidatorInterface */
  private ModelValidatorInterface $validator;

  /**
   * POST model constructor.
   *
   * @param array $requestData
   * @param ErrorHandlerInterface $errorHandler
   * @param ConnectionInterface $connection
   * @param ModelValidatorInterface $validator
   */
  public function __construct(
    array $requestData,
    ErrorHandlerInterface $errorHandler,
    ConnectionInterface $connection,
    ModelValidatorInterface $validator
  ) {
    $this->requestData  = $requestData;
    $this->errorHandler = $errorHandler;
    $this->connection   = $connection;
    $this->validator    = $validator;
    $this->table        = $requestData['table'];
    $this->columns      = [];
    $this->values       = [];
    $this->query        = '';
    $this->database     = null;
    $this->stmt         = null;
  }

  /**
   * Inserts a record into the database.
   *
   * @return array
   * @throws \InvalidArgumentException
   * @throws \RuntimeException
   */
  public function start(): array
  {
    $this->cleanRequestData();
    $this->prepareRequestData();
    $this->validator->validateTable($this->table);

    $this->prepareQuery();
    return $this->executeQuery();
  }

  private static function setQuery(string $table, array $columns): string
  {
    $values  = PostModel::setValues($columns);
    $columns = PostModel::setColumns($columns);
    return "INSERT INTO `$table` ($columns) VALUES  ($values) ";
  }

  private static function setColumns(array $columns): string
  {
    return implode(', ', array_map(fn($col) => "`$col`", $columns));
  }

  private static function setValues(array $columns): string
  {
    return implode(', ', array_map(fn($col) => ":$col", $columns));
  }

  private static function parseIsMenuDay(mixed $value): string
  {
    return match ($value) {
      true,  'true',  'TRUE',  '1', 1 => "1",
      false, 'false', 'FALSE', '0', 0 => "0",
      default => $value
    };
  }

  private function cleanRequestData(): void
  {
    unset($this->requestData['table']);
    unset($this->requestData['action']);
    $this->requestData['is_menu_day'] = self::parseIsMenuDay($this->requestData['is_menu_day']);
  }

  private function prepareRequestData(): void
  {
    foreach ($this->requestData as $key => $value) {
      $this->validator->validateColumn($this->table, $key);
      $this->columns[] = $key;
      $this->values[]  = $value;
    }
  }

  private function prepareQuery(): void
  {
    try {
      $this->query = self::setQuery($this->table, $this->columns);
      $this->database  = $this->connection->connect();
      $this->stmt  = $this->database->prepare($this->query);

      foreach ($this->columns as $index => $key) {
        $this->stmt->bindParam(":" . $key, $this->values[$index], PDO::PARAM_STR);
      }
    } catch (\Exception $err) {
      $this->errorHandler->logDatabaseError($err, 'postData');
      throw new \RuntimeException("Unexpected error: " . $err->getMessage(), 0, $err);
    }
  }

  private function executeQuery(): array
  {
    try {
      $this->stmt->execute();
      $lastInsertedId = $this->database->lastInsertId();

      if ($lastInsertedId == "0" || $lastInsertedId == 0) {
        $lastInsertedId = $this->requestData["id"] ?? null;
      }

      return [
        'insertedId' =>  $lastInsertedId
      ];

    } catch (PDOException $err) {
      $this->errorHandler->logDatabaseError($err, 'postData');
      throw new \RuntimeException("Database error: " . $err->getMessage(), 0, $err);
    }
  }
}
