<?php

namespace models;

use app\Connection;
use app\HttpResponse;


use PDO;
use PDOException;

class POSTModel
{

  private $user;
  private $password;
  private $validPassword;
  private $token;
  private $userData;
  private $httpResponse;


  public function __construct($user, $password, $token)
  {
    $this->autoLoad();

    $this->user           = $user;
    $this->password       = $password;
    $this->validPassword  = FALSE;
    $this->token          = $token;
    $this->userData       = FALSE;
    $this->httpResponse   = new HttpResponse();
  }

  private function autoLoad()
  {
    require_once "../app/Connection.php";
    require_once "../app/HttpResponse.php";
    require_once "../app/auth.config.php";
  }

  private function createToken()
  {
    $newToken = bin2hex(openssl_random_pseudo_bytes(32));

    try {
      $query = "UPDATE `" . AUTH_TABLE . "` SET " . AUTH_HTTP_TOKEN . " = :newToken WHERE `" . AUTH_USERNAME_FIELD . "` = :user";

      $stmt = Connection::connect()->prepare($query);

      $stmt->bindParam(":newToken", $newToken,   PDO::PARAM_STR);
      $stmt->bindParam(":user",     $this->user, PDO::PARAM_STR);
      




      if ($stmt->execute()) {

        $this->token = $newToken;
        return;
      }
    } catch (PDOException $err) {

      error_log("ERROR IN CLASS POSTModel->createToken: " . $err->getMessage());
      return $this->httpResponse->getStatus500('Token creation Failed');
    }
  }

  private function getUserData()
  {
    try {
      $query = "SELECT * FROM `" . AUTH_TABLE . "` WHERE `" . AUTH_USERNAME_FIELD . "` = :user LIMIT 1";
      $stmt = Connection::connect()->prepare($query);

      $stmt->bindParam(":user", $this->user, PDO::PARAM_STR);
      $stmt->execute();

      $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (count($userData) > 0) {

        return $userData[0];
      } else {

        return FALSE;
      }
    } catch (PDOException $err) {

      error_log("ERROR IN CLASS POSTModel->getUserData: " . $err->getMessage());
      return $this->httpResponse->getStatus500('Consulting user Failed');
    }
  }

  public function  validateUser()
  {

    //Getting user data
    $this->userData = $this->getUserData();

    //is There user data? then validate the password
    if ($this->userData) $this->validatePassword();


    //Is the pasword valid? then create the token
    if ($this->validPassword) $this->createToken($this->userData);

    if ($this->userData && $this->token) {
      
      unset($this->userData[AUTH_PASSWORD_FIELD]);

      echo $this->httpResponse->getStatus200($this->userData);
    } elseif (!$this->userData) {

      echo $this->httpResponse->getStatus404('User not found');
    } elseif (!$this->validPassword) {

      echo $this->httpResponse->getStatus400('Invalid password');
    }

    return;
  }

  private function validatePassword()
  {
    if (password_verify($this->password, $this->userData[AUTH_PASSWORD_FIELD])) {

      $this->validPassword = TRUE;
      return;
    } else {

      return FALSE;
    }
  }
}
