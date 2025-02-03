<?php
namespace controllers;
use models\POSTModel;
use app\HttpResponse;

require_once "./models/POSTModel.php";

class POSTController
{
  private $user     = '';
  private $password = '';
  private $token    = '';

  public function __construct()
  {
    $this->user     = $_POST['user']     ?? NULL;
    $this->password = $_POST['password'] ?? NULL;
    $this->token    = $_POST['token']    ?? NULL;
  }

  public function authentication()
  {
    if (isset($this->user) && isset($this->password)) {
      $model = new POSTModel($this->user, $this->password, $this->token);
      $model->validateUser();
    } else {

      $httpResponse = new HttpResponse();
      return $httpResponse->getStatus400('Missing Credentials');
    }
  }
}
