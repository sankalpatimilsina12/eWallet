<?php
  require_once("../resources/jwt/vendor/autoload.php");
  require_once("connection.php");
  use \Firebase\JWT\JWT;
  define('ALGORITHM', 'HS512');
  define('SECRET_KEY', 'YOUR-SECRET-KEY');

  // Get post parameters.
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  // Verify user in database.
  $db = new Connect;
  $query = "SELECT * FROM user WHERE email='$email' AND password='$password'";
  $result = mysqli_query($db->getConnection(), $query);

  if($result->num_rows == 0) {
    echo 0;  
    exit;
  }

  // Create token.
  $tokenId = base64_encode(mcrypt_create_iv(32));
  $issuedAt = time();
  $notBefore  = $issuedAt + 10;  //Adding 10 seconds
  $expire     = $notBefore + 7200; // Adding 60 seconds
  $serverName = 'http://localhost/eWallet';
  $data = [
    'iat' => $issuedAt,
    'jti' => $tokenId,
    'iss' => $serverName,
    'nbf' => $notBefore,
    'exp' => $expire,
    'data' => [
      'email' => $email,
      'password' => $password
    ]
  ];
  $secretKey = base64_encode(SECRET_KEY);
  $jwt = JWT::encode(
    $data, 
    $secretKey,
    ALGORITHM
  );

  // Return token to client.
  echo json_encode($jwt);
  exit;