<?php
require_once "models/get.model.php";





class GetController
{
    public static  function  getResponse($ARGUMENTS, $HTTPRESPONSE)
    {
        $queryData = GetModel::getData($ARGUMENTS);

        return GetController::setResponse($queryData, $HTTPRESPONSE);
    }

    public static  function  getResponseFilter($ARGUMENTS, $HTTPRESPONSE)
    {
        $queryData = GetModel::getDataFilter($ARGUMENTS);
        return GetController::setResponse($queryData, $HTTPRESPONSE);
    }

    private static function setResponse($QUERY_DATA, $HTTPRESPONSE)
    {

        $PDOExeption = isset($QUERY_DATA['PDOException']);

        if ($PDOExeption) {
            echo $HTTPRESPONSE->getStatus404($QUERY_DATA['PDOException']);

        } else if (isset($QUERY_DATA)) {
            echo $HTTPRESPONSE->getStatus200($QUERY_DATA, count($QUERY_DATA));
        }

        return;
    }
}
