<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('ignore_repeated_errors', TRUE);
ini_set("error_log", "./error.rest.log");
//---------------------------------------
use app\HttpResponse;

autoLoad();

$httpResponse = new HttpResponse();
$routerController = new RouterController($httpResponse);

$routerController->loadRoute();


function autoLoad()
{
  
  require_once "controllers/router.controller.php";

  require_once "../app/Connection.php";
  require_once "../app/cors.php";
  require_once "../app/HttpResponse.php";
}
