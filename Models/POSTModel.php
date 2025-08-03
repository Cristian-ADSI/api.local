<?php

namespace Services\Rest\Models;

use Services\Config\ConnectionInterface;
use Services\Rest\Interfaces\ErrorHandlerInterface;

class POSTModel
{
  private ConnectionInterface $connection;
  private ErrorHandlerInterface $errorHandler;
  private array $requestData;

  public function __construct(
    array $requestData,
    ErrorHandlerInterface $errorHandler,
    ConnectionInterface $connection
  ) {
    $this->requestData  = $requestData;
    $this->errorHandler = $errorHandler;
    $this->connection   = $connection;
  }

  public static function start(): string
  {
    // $table   = $TABLE;
    // $columns = [];
    // $values  = [];

    // foreach ($POSTDATA as $key => $value) {
    //   array_push($columns, $key);
    //   array_push($values,  $value);
    // }

    // try {
    //   $query = PostModel::setQuery($table, $columns);
    //   $stmt  = Connection::connect()->prepare($query);

    //   foreach ($columns as $index => $value) {
    //     $stmt->bindParam(":" . $value, $values[$index], PDO::PARAM_STR);
    //   }

    //   $stmt->execute();
    //   $lastInsertedId = Connection::connect()->lastInsertId();

    //   if ($lastInsertedId == "0") {
    //     $lastInsertedId = $values[0];
    //   }

    //   return [
    //     'insertedId' =>  $lastInsertedId
    //   ];

    // } catch (PDOException $err) {
    //   error_log('ERROR::PostModel=>postData() ' . $err->getMessage());

    //   return ["PDOException" => $err->getMessage()];
    // }

    return "Start Post";
  }

  private static function setQuery($TABLE, $COLUMNS)
  {
    $VALUES =  PostModel::setValues($COLUMNS);
    $COLUMNS = PostModel::setColumns($COLUMNS);

    $query = "INSERT INTO `$TABLE` ($COLUMNS) VALUES  ($VALUES) ";

    return $query;
  }

  private static function setColumns($COLUMNS)
  {
    $columns = "";

    foreach ($COLUMNS as $key => $value) {
      $columns .= "`$value`, ";
    }

    return substr($columns, 0, -2);
  }

  private static function setValues($COLUMNS)
  {
    $values =  "";
    foreach ($COLUMNS as $key => $value) {


      $values .= ":$value, ";
    }

    return substr($values, 0, -2);
  }
}
