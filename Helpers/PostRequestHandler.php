<?php

namespace Services\RestService\Helpers;

use Services\RestService\Helpers\ConfigurationService;
use Services\RestService\Helpers\ErrorHandler;
use Services\RestService\Interfaces\PostRequestHandlerInterface;
use Services\RestService\Models\POSTModel;
use Services\Utils\HttpResponses;

class PostRequestHandler implements PostRequestHandlerInterface
{
  public function handle(array $requestData, HttpResponses $httpResponse): array
  {
    return $this->postResponse($requestData, $httpResponse);
  }

  public function postResponse(array $requestData, HttpResponses $httpResponse): array
  {
    $configService = new ConfigurationService();
    $dbConfig = $configService->getDatabaseConfig();

    $errorHandler = new ErrorHandler();
    $connection = new \Services\Config\MySQLConnection($dbConfig['host'], $dbConfig['name'], $dbConfig['user'], $dbConfig['pass']);
    $validator = new ModelDataValidator();
    $postModel = new POSTModel($requestData, $errorHandler, $connection, $validator);

    return  $postModel->start();
  }

  public function setResponse(array $responseData, HttpResponses $httpResponse): array
  {
    $PDOException = isset($responseData['PDOException']);
    $response = [];

    if ($PDOException) {
      $response = $httpResponse->getStatus400($responseData['PDOException']);
    } else if (isset($responseData)) {
      $response = $httpResponse->getStatus200($responseData, count($responseData));
    }
    return $response;
  }
}
