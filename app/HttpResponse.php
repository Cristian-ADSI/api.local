<?php

namespace app;
class HttpResponse
{

  private $response = [
    'details' => '',
    'message' => '',
    'code'    => '',
    'total'   => 0,
    'data' => ''
  ];

  public function getStatus200($data, $total=0)
  {
    $this->response['total']    = $total;
    $this->response['details']  = 'The request has been successfull';
    $this->response['message']  = 'ok';
    $this->response['code']     = 200;
    $this->response['data'] = $data;

    http_response_code(200);
    echo json_encode($this->response,JSON_UNESCAPED_UNICODE);

    return;
  }

  public function getStatus400($details = "Invalid argument (invalid request payload)")
  {
    $this->response['details']  = $details;
    $this->response['message']  = 'Bad Request';
    $this->response['code']     = 400;
    http_response_code(400);
    echo json_encode($this->response,JSON_UNESCAPED_UNICODE);

    return;
  }

  public function getStatus401()
  {
    $this->response['details']  = "Authentication is required to obtain the requested response";
    $this->response['message']  = 'Unauthorized';
    $this->response['code']     = 401;
    
    http_response_code(401);
    echo json_encode($this->response,JSON_UNESCAPED_UNICODE);

    return;
  }
  public function getStatus403()
  {
    $this->response['details']  = "Not enough permissions, invalid or expired token";
    $this->response['message']  = 'forbiden';
    $this->response['code']     = 403;
    
    http_response_code(401);
    echo json_encode($this->response,JSON_UNESCAPED_UNICODE);

    return;
  }

  public function getStatus404($details = 'The requested content is not available')
  {
    $this->response['details']  = $details;
    $this->response['message']  = 'Not Found';
    $this->response['code']     = 404;
    
    http_response_code(404);
    echo json_encode($this->response,JSON_UNESCAPED_UNICODE);

    return;
  }

  public function getStatus405()
  {
    $this->response['details']  = "The requested method is known by the server but is not available for this endpoint";
    $this->response['message']  = 'Method not allowed';
    $this->response['code']     = 405;
    
    http_response_code(405);
    echo json_encode($this->response,JSON_UNESCAPED_UNICODE);

    return;
  }

  public function getStatus500($details)
  {
    $this->response['details']  = $details;
    $this->response['message']  = 'Internal server error';
    $this->response['code']     = 500;
    
    http_response_code(405);
    echo json_encode($this->response,JSON_UNESCAPED_UNICODE);

    return;
  }
}
