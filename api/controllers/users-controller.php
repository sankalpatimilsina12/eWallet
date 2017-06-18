<?php
  require_once('base-controller.php');
  use \Firebase\JWT\JWT;
  
  class Users extends BaseController {
    private $db, $query, $result, $data;
    private $username, $email, $password;
    private $token, $tokenId, $notBefore, $expire, $issuedAt, $serverName;
    private $characters, $charactersLength, $length, $randomString;
    private $secretKey, $jwt;
    private $errors, $temp;

    public function __construct() {
      parent::__construct();

      $this->errors = [
        'login-error' => 'Incorrect username or password.',
        'register-error' => 'Incorrect information.',
        'forgot-password-error' => 'No such email found.',
        'token-error' => 'Incorrect or empty token or password.',
      ];

      $this->db = new Connect;

      switch($this->method) {
        case 'GET':
          switch($this->action) {
            case 'getUsers':
              if(isset($this->params)) {
                $this->temp = $this->params['param1'];
                $this->query = "SELECT * FROM user WHERE user.id = '$this->temp'";
              } else {
                $this->query = "SELECT * FROM user";
              }
              $this->runQuery();

              if($this->result->num_rows > 0) {
                $this->data = mysqli_fetch_all($this->result);
                echo json_encode($this->data);
              } else {
                echo "No user(s) found!";
              }
            break;

            default:
              $this->_response('Incorrect Method or Action.', 405);
            break;
          }
        break;

        case 'POST':
          switch($this->action) {
            case 'login':
              if(isset($this->input['email'], $this->input['password']) and $this->input['email'] != "" and $this->input['password'] != "") {
                // Pass to login controller for verification and response.
                $this->login();
              }
              else {
                echo $this->errors['login-error'];
              }
            break;

            case 'register':
              if(isset($this->input['username'], $this->input['email'], $this->input['password']) and $this->input['username'] != ""
                and $this->input['email'] != "" and $this->input['password'] != "") {
                  // Register the user in the database.
                  $this->register();
              }
              else {
                echo $this->errors['register-error'];
              }
            break;

            case 'mailforgotpassword':
              if(isset($this->input['email']) and $this->input['email'] != "") {
                // Verify email in database and update or create token. 
                $this->mail_forgot_password();
              }
              else {
                echo $this->errors['forgot-password-error'];
              }
            break;

            default:
              $this->_response('Incorrect Method or Action.', 405);
            break;
          }
        break;
          
        case 'PUT':
          switch($this->action) {
            case 'verifytoken':
              if(isset($this->input['token'], $this->input['password']) and $this->input['token'] != "" and $this->input['password'] != "") {
                $this->verifyToken();
              }
              else {
                echo $this->errors['token-error'];
              }
              break;
            default:
              $this->_response('Incorrect Method or Action.', 405);
              break;
          }
        break;

        case 'DELETE':
          if(isset($this->action)) {
            switch($this->action) {
              case 'delete':
                $this->query = "DELETE FROM user";
                if(isset($this->params)) {
                  $this->temp = $this->params['param1'];
                  $this->query = "SELECT * FROM user WHERE user.id = '$this->temp'";
                  $this->runQuery();
                  if($this->result->num_rows > 0)
                    $this->query = "DELETE FROM user WHERE user.id = '$this->temp'";
                  else {
                    echo "No such record found to delete.";
                    exit;
                  }
                }
                $this->runQuery();
                if($this->result == 1)
                  echo "Record deleted successfully.";
                else
                  echo "No such record found.";
              break;
              default:
                $this->_response('Incorrect Method or Action.', 405);
              break; 
            }
          } 
        break;
      }
    }

    protected function runQuery() {
      $this->result = mysqli_query($this->db->getConnection(), $this->query);
    }
    protected function login() {
      // Get post parameters.
      $this->email = $this->input['email'];
      $this->password = md5($this->input['password']);

      // Verify user in database.
      $this->query = "SELECT * FROM user WHERE email='$this->email' AND password='$this->password'";
      $this->runQuery();


      if($this->result->num_rows == 0) {
        echo $this->errors['login-error'];
        exit;
      }

      // Create token.
      $this->tokenId = base64_encode(mcrypt_create_iv(32));
      $this->issuedAt = time();
      $this->notBefore  = $this->issuedAt + 10;  //Adding 10 seconds
      $this->expire     = $this->notBefore + 7200; // Adding 60 seconds
      $this->serverName = 'http://localhost/eWallet';
      $this->data = [
        'iat' => $this->issuedAt,
        'jti' => $this->tokenId,
        'iss' => $this->serverName,
        'nbf' => $this->notBefore,
        'exp' => $this->expire,
        'data' => [
          'email' => $this->email,
          'password' => $this->password
        ]
      ];

      $this->secretKey = base64_encode(SECRET_KEY);
      $this->jwt = JWT::encode(
        $this->data, 
        $this->secretKey,
        ALGORITHM
      );

      // Return token to client.
      echo json_encode($this->jwt);
    }
    protected function register() {
      // Get post parameters.
      $this->username = $this->input['username'];
      $this->email = $this->input['email'];
      $this->password = md5($this->input['password']);

      // Create user in database.
      $this->query = "INSERT INTO user (`username`, `email`, `password`)
                VALUES ('$this->username', '$this->email', '$this->password')";
      $this->runQuery();
      
      // Create token with all inserted data.
      $this->tokenId = base64_encode(mcrypt_create_iv(32));
      $this->issuedAt = time();
      $this->notBefore  = $this->issuedAt + 10;  //Adding 10 seconds
      $this->expire     = $this->notBefore + 7200; // Adding 60 seconds
      $this->serverName = 'http://localhost/eWallet';
      $this->data = [
        'iat' => $this->issuedAt,
        'jti' => $this->tokenId,
        'iss' => $this->serverName,
        'nbf' => $this->notBefore,
        'exp' => $this->expire,
        'data' => [
          'username' => $this->username,
          'email' => $this->email,
          'password' => $this->password
        ]
      ];

      $this->secretKey = base64_encode(SECRET_KEY);
      $this->jwt = JWT::encode(
        $this->data, 
        $this->secretKey,
        ALGORITHM
      );

      // Return token to client.
      echo json_encode($this->jwt);
    }
    protected function mail_forgot_password() {
      // Get post email.
      $this->email = $this->input['email'];
      //$this->email = $_POST['email'];

      // Verify in database.
      $this->query = "SELECT * FROM user WHERE email='$this->email'";
      $this->runQuery();

      if($this->result->num_rows == 0) {
        echo $this->errors['forgot-password-error'];
        exit;
      }

      else {
        // Generate random token.
        $this->characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $this->charactersLength = strlen($this->characters);
        $this->length = 6;
        $this->randomString = '';
        for ($i = 0; $i < $this->length; $i++) {
            $this->randomString .= $this->characters[rand(0, $this->charactersLength - 1)];
        }

        // Send mail with the generated token.
        sendMail("sankalpatimilsina12@gmail.com", $this->email, "eWallet", $this->randomString, "sankalpatimilsina12@gmail.com");

        // Push token in database.
        $this->query = "SELECT * FROM token WHERE email='$this->email'";
        $this->runQuery();

        if($this->result->num_rows > 0)
          $this->query = "UPDATE token SET token='$this->randomString' WHERE email='$this->email'";
        else
          $this->query = "INSERT INTO token (`email`, `token`) VALUES ('$this->email', '$this->randomString')";

        $this->runQuery();

        echo "Mail has been sent to ". $this->email;
      }
    }
    protected function verifyToken() {
      $this->token = trim($this->input['token']);
      $this->email = $this->input['email'];
      $this->password = md5($this->input['password']);

      $this->query = "SELECT * FROM token WHERE email='$this->email' AND token='$this->token'";
      $this->runQuery();
      $data = mysqli_fetch_all($this->result);

      // Token verification.
      if($this->result->num_rows > 0) {
          $this->query = "UPDATE user SET password='$this->password' WHERE email='$this->email'";
          $this->runQuery();
          echo 'Token verification successfull.';
      }
      else 
        echo $this->errors['token-error'];
    }
  }

$users = new Users();

