<?php
  require_once("../resources/jwt/vendor/autoload.php");
  require_once("connection.php");
  require_once("mail.php");
  use \Firebase\JWT\JWT;
  define('ALGORITHM', 'HS512');
  define('SECRET_KEY', 'apple');

  // Get post email.
  $email = $_POST['email'];

  // Verify in database.
  $db = new Connect;
  $query = "SELECT * FROM user WHERE email='$email'";
  $result = mysqli_query($db->getConnection(), $query);

  if($result->num_rows > 0) {
    // Generate random token.
    // $tokenId = base64_encode(mcrypt_create_iv(32));
    // $issuedAt = time();
    // $notBefore  = $issuedAt + 10;  //Adding 10 seconds
    // $expire     = $notBefore + 7200; // Adding 60 seconds
    // $serverName = 'http://localhost/eWallet';
    // $data = [
    //   'iat' => $issuedAt,
    //   'jti' => $tokenId,
    //   'iss' => $serverName,
    //   'nbf' => $notBefore,
    //   'exp' => $expire,
    //   'email' => $email
    // ];
    // $secretKey = base64_decode(SECRET_KEY);
    // $jwt = JWT::encode(
    //   $data, 
    //   $secretKey,
    //   ALGORITHM
    // );
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $length = 6;
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    // Send mail with the generated token.
    sendMail("sankalpatimilsina12@gmail.com", $email, "eWallet", $randomString, "sankalpatimilsina12@gmail.com");

    // Push token in database.
    $query = "SELECT * FROM token WHERE email='$email'";
    $result = mysqli_query($db->getConnection(), $query);
    if($result->num_rows > 0)
      $query = "UPDATE token SET token='$randomString' WHERE email='$email'";
    else
      $query = "INSERT INTO token (`email`, `token`) VALUES ('$email', '$randomString')";
      $result = mysqli_query($db->getConnection(), $query);
      
  }