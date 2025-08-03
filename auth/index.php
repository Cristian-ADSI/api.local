<?php
error_reporting(E_ALL);

ini_set('display_errors', TRUE);// FALSE only in PRODUCTION
ini_set('ignore_repeated_errors', TRUE);
ini_set("error_log", "./error.auth.log");

use app\HttpResponse;
use controllers\POSTController;

autoLoad();


$method = $_SERVER['REQUEST_METHOD'];

if ($method != 'POST') {

  $httpResponse = new HttpResponse();
  return $httpResponse->getStatus405();

} else {

  $controller = new POSTController();
  $controller->authentication();
}

//============================================
function autoLoad()
{
  require_once "../app/HttpResponse.php";
  require_once "./controllers/POSTController.php";
}
