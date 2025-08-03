<?php
namespace Services\API\Models;
use app\Connection;

class PutModel
{


  public static function putData($PUT_DATA, $GET_DATA, $TABLE)
  {
    $table   = $TABLE;
    $columns = [];
    $values  = [];
    $KeyColumn     = $GET_DATA['keyColumn'];
    $keyValue      = $GET_DATA['keyValue'];

    foreach ($PUT_DATA as $index => $value) {
      array_push($columns, $index);
      array_push($values,  $value);
    }


    try {
      $query = PutModel::setQuery($table, $columns, $KeyColumn);
      $stmt  = Connection::connect()->prepare($query);

      foreach ($columns as $index => $value) {
        $stmt->bindParam(":" . $value, $values[$index], PDO::PARAM_STR);
      }

      $stmt->bindParam(":" . $KeyColumn, $keyValue, PDO::PARAM_STR);

      $stmt->execute();
      $lastInsertedId = Connection::connect()->lastInsertId();

      if ($lastInsertedId == "0") {
        $lastInsertedId = $keyValue;
      }

      return [
        'updatedId' =>  $lastInsertedId
      ];
    } catch (PDOException $err) {
      error_log('ERROR::PostModel=>postData() ' . $err->getMessage());

      return ["PDOException" => $err->getMessage()];
    }
  }

  private static function setQuery($TABLE, $COLUMNS, $KEY)
  {

    $columns = PutModel::setColumns($COLUMNS);


    $query = "UPDATE `$TABLE` SET $columns WHERE `$KEY` = :$KEY ";

    return $query;
  }

  private static function setColumns($COLUMNS)
  {
    $columns = "";

    foreach ($COLUMNS as $index => $value) {
      $columns .= "`$value` = :$value, ";
    }

    return substr($columns, 0, -2);
  }
}
