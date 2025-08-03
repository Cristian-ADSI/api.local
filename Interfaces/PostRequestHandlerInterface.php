<?php

namespace Services\Rest\Interfaces;

use Services\Utils\HttpResponses;

interface PostRequestHandlerInterface
{
  public function handle(array $requestData, HttpResponses $httpResponse): void;
  public function postResponse(array $requestData, HttpResponses $httpResponse): string;
  public function setResponse(array $responseData, HttpResponses $httpResponse): string;
}
