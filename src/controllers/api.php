<?php
require_once('../resources/jwt/vendor/autoload.php');
use \Firebase\JWT\JWT;
define('ALGORITHM', 'HS512');
define('SECRET_KEY', 'abcde');

// if($_GET['action'] == 'login') {
//   $name = $_SERVER['PHP_AUTH_USER'];
//   $password = $_SERVER['PHP_AUTH_PW'];

//   // Get the login details from the database
//   $row = [];
//   $row[0]['name'] = 'sankalpa';
//   $row[0]['password'] = 'timilsina';


// // Token generation
//   if(count($row) > 0)
//   {
//     $tokenId = base64_encode(mcrypt_create_iv(32));
//     $issuedAt = time();
//     $notBefore  = $issuedAt + 10;  //Adding 10 seconds
//     $expire     = $notBefore + 7200; // Adding 60 seconds
//     $serverName = 'http://localhost/Rest';

//     // Create the token as an array
//     $data = [
//       'iat' => $issuedAt,
//       'jti' => $tokenId,
//       'iss' => $serverName,
//       'nbf' => $notBefore,
//       'exp' => $expire,
//       'data' => [
//         'name' => $row[0]['name'],
//         'password' => $row[0]['password'],
//       ]
//     ];
//     $secretKey = base64_encode(SECRET_KEY);
    
//     // Transfrom the array to JWT
//     $jwt = JWT::encode(
//       $data, 
//       $secretKey,
//       ALGORITHM
//     );
//     setcookie('cookieName', $jwt);
//   }
// }

// else if($_GET['action'] == 'authenticate') {
//   $secretKey = base64_decode(SECRET_KEY); 
//   $DecodedDataArray = JWT::decode(
//     $_COOKIE['cookieName'],
//     $secretKey,
//     array(ALGORITHM)
//   );
//   echo  "{'status' : 'success' ,'data':".json_encode($DecodedDataArray)." }"; 
// }

// else {
//   echo "Invalid Request";
//   die();
// }

abstract class API
{
  protected $method;
  protected $body;
  protected $route;
  protected $params;

  public function __construct() {
    $this->params = [];

    if(isset($_GET['route'], $_GET['param1'], $_GET['param2']))
      $this->setRoute($_GET['route'])->setParamFirst($_GET['param1'])->setParamSecond($_GET['param2']);
    
    else if(isset($_GET['route'], $_GET['param1']))
      $this->users = $this->setRoute($_GET['route'])->setParamFirst($_GET['param1']);

    else
      $this->users = $this->setRoute($_GET['route']);

    $this->method = $_SERVER['REQUEST_METHOD'];
    $secretKey = base64_decode(SECRET_KEY); 
    $headers = getallheaders();
   
    if(isset($headers['access_token'])) {
      $this->_isRouteValid($this->route);
      // if(isset($_COOKIE['cookieName']))
      //$this->_response('Looks Great!');
      // $DecodedDataArray = JWT::decode(
      //   $_COOKIE['cookieName'],
      //   $secretKey,
      //   array(ALGORITHM)
      // );
      // echo  "{'status' : 'success' ,'data':".json_encode($DecodedDataArray)." }"; 
    }
    else {
      $this->_response('Authentication Failed', 401);
      die;
    }

    switch($this->method) {
    case 'GET':
          break;
    case 'POST':
          $this->body = json_decode(file_get_contents("php://input"), true);
          break;
    case 'PUT':
          $this->body = json_decode(file_get_contents("php://input"), true);
          break;
    default:
          $this->_response('Invalid Method', 405);
          break;
    }
  }

  protected function _response($data, $status = 200) {
    header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
    echo $data;
    return json_encode($data);
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
    $routes = array(
      'users',
      'images',
    );
    $flag = false;

    if(!in_array($request, $routes)) {
      $this->_response('No routes found', 404);
      die;
    }
  }

  public function setRoute($route) {
    $this->route = $route;
    return $this;
  }

  public function setParamFirst($paramFirst) {
    $this->params['param1'] = $paramFirst;
    return $this;
  }

  public function setParamSecond($paramSecond) {
    $this->params['param2'] = $paramSecond;
    return $this;
  }

}

class USERS extends API
{
  protected $users;

  public function __construct() {
    parent::__construct();

    switch($this->method) {
      case 'GET':
        $this->_response("hello world ".$this->params['param1']);
        break;
    }
  }

}

$users = new USERS;
?>