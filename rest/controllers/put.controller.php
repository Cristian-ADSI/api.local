<?php
require_once "models/put.model.php";


class PutController
{


  public static function putResponse($POST_DATA, $GET_DATA, $TABLE, $HTTPRESPONSE)
  {
    $queryData = PutModel::putData($POST_DATA, $GET_DATA, $TABLE);

    return PutController::setResponse($queryData, $HTTPRESPONSE);
  }

  private static function setResponse($QUERY_DATA, $HTTPRESPONSE)
  {
    $PDOExeption = isset($QUERY_DATA['PDOException']);

    if ($PDOExeption) {
      echo $HTTPRESPONSE->getStatus400($QUERY_DATA['PDOException']);
    } else if (isset($QUERY_DATA)) {
      echo $HTTPRESPONSE->getStatus200($QUERY_DATA, count($QUERY_DATA));
    }

    return;
  }
}
