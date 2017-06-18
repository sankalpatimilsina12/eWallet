<?php
require_once('connection.php');
require_once('mail.php');
require_once('../resources/jwt/vendor/autoload.php');
define('ALGORITHM', 'HS512');
define('SECRET_KEY', 'abcde');

abstract class BaseController
{
  private $routes, $actions;
  protected $method, $route, $params, $paramsTemp, $action, $input;

  public function __construct() {
    if(isset($_GET['params'])) {
      $this->paramsTemp = $_GET['params'];

      // Create associative array for params.
      for($i = 0; $i < count($this->paramsTemp); $i++) {
        $this->params['param' . ($i + 1)] = $this->paramsTemp[$i];
      }
    }

    if(isset($_GET['route'], $_GET['action']))
      $this->setRoute($_GET['route'])->setAction($_GET['action']);
    else
      $this->setRoute($_GET['route']);

    $this->method = $_SERVER['REQUEST_METHOD'];
    $secretKey = base64_decode(SECRET_KEY); 
    $headers = getallheaders();

    // Check route validity.
    $this->_isRouteValid($this->route);

    // If action present, check action validity.
    if(isset($this->action))
      $this->_isActionValid($this->action);

   
    switch($this->method) {
    case 'GET':
          break;
    case 'POST':
          $this->input = json_decode(file_get_contents('php://input'), true);
          break;
    case 'PUT':
          $this->input = json_decode(file_get_contents('php://input'), true);
          break;
    case 'DELETE':
          break;
    default:
          $this->_response('Invalid Method', 405);
          break;
    }
  }

  protected function _response($data, $status = 200) {
    header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
    echo $data . "\n";
  }

  protected function _requestStatus($code) {
    $status = array(  
        200 => 'OK',
        401 => 'Unauthorized',
        404 => 'Not Found',   
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    ); 
    return ($status[$code])?$status[$code]:$status[500]; 
  }

  protected function _isRouteValid($request) {
    $this->routes = array(
      /* Routes */
      'users',
    );

    if(!in_array($request, $this->routes)) {
      $this->_response('Route (' . $request . ') not found.', 404);
      exit;
    }
  }

  protected function _isActionValid($request) {
    $this->actions = array(
      /* Actions */
      'getUsers',
      'login',
      'register',
      'mailforgotpassword',
      'verifytoken',
      'delete'
    );

    if(!in_array($request, $this->actions)) {
      $this->_response('Action (' . $request . ') not found.', 404);
      exit;
    }
  }

  public function setRoute($route) {
    $this->route = $route;
    return $this;
  }

  public function setAction($action) {
    $this->action = $action;
    return $this;
  }
}
