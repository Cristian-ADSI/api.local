<?php

namespace app;

use PDO;
use PDOException;

include_once("database.php");

class Connection
{
  static private $con;

  static public function connect()
  {

    $dsn = "mysql:host=localhost;charset=utf8mb4;dbname=" . DBNAME;
    try {
      self::$con = new PDO($dsn , USER, PASSWORD);

      self::$con->exec("set names utf8");

      return self::$con;
    } catch (PDOException $err) {

      error_log("ERROR IN CLASS Connection->connect:" . $err->getMessage());
      die();
    }
  }
}
