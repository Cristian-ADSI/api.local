<?php

namespace Services\RestService\Interfaces;

use Services\Utils\HttpResponses;

interface PostRequestHandlerInterface
{
  public function handle(array $requestData, HttpResponses $httpResponse): array;
  public function postResponse(array $requestData, HttpResponses $httpResponse): array;
  public function setResponse(array $responseData, HttpResponses $httpResponse): array;
}
