<?php

namespace Services\RestService\Controllers;

use Services\RestService\Interfaces\PostRequestHandlerInterface;
use Services\Utils\HttpResponses;


class POSTController
{
  private   PostRequestHandlerInterface $postRequestHandler;
  private   HttpResponses $httpResponses;

  public function __construct(
    PostRequestHandlerInterface $postRequestHandler,
    HttpResponses $httpResponses
  ) {
    $this->postRequestHandler = $postRequestHandler;
    $this->httpResponses = $httpResponses;
  }

  public function createPost($requestData):array
  {
    return $this->postRequestHandler->handle($requestData, $this->httpResponses);
  }



  // public static function postResponse($POSTDATA, $TABLE, $HTTPRESPONSE)
  // {
  //   $queryData = PostModel::postData($TABLE, $POSTDATA);

  //   return PostController::setResponse($queryData, $HTTPRESPONSE);
  // }

}
