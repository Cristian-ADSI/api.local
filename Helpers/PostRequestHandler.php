<?php

namespace Services\Rest\Helpers;

use Services\Rest\Interfaces\PostRequestHandlerInterface;
use Services\Utils\HttpResponses;
use Services\Rest\Helpers\ConfigurationService;
use Services\Rest\Models\POSTModel;

class PostRequestHandler implements PostRequestHandlerInterface
{
  public function handle(array $requestData, HttpResponses $httpResponse): void
  {
    $this->postResponse($requestData, $httpResponse);
  }

  public function postResponse(array $requestData, HttpResponses $httpResponse): string
  {
    $configService = new ConfigurationService();
    $dbConfig = $configService->getDatabaseConfig();

    $errorHandler = new ErrorHandler();
    $connection = new \Services\Config\MySQLConnection($dbConfig['host'], $dbConfig['name'], $dbConfig['user'], $dbConfig['pass']);
    $postModel = new POSTModel($requestData, $errorHandler, $connection);

    return  $postModel->start();
  }

  public function setResponse(array $responseData, HttpResponses $httpResponse): string
  {
    $PDOException = isset($responseData['PDOException']);
    $response = '';

    if ($PDOException) {
      $response = $httpResponse->getStatus400($responseData['PDOException']);
    } else if (isset($responseData)) {
      $response = $httpResponse->getStatus200($responseData, count($responseData));
    }
    return $response;
  }
}
