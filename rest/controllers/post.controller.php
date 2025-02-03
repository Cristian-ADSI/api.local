<?php
require_once "models/post.model.php";

class PostController
{
  public static function postResponse($POSTDATA, $TABLE, $HTTPRESPONSE)
  {
    $queryData = PostModel::postData($TABLE, $POSTDATA);

    return PostController::setResponse($queryData, $HTTPRESPONSE);
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
