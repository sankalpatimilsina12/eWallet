<?php
  require_once("connection.php");

  $token = $_POST['token'];
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  $db = new Connect;
  $query = "SELECT token FROM token WHERE email='$email'";
  $result = mysqli_query($db->getConnection(), $query);
  $row = $result->fetch_all();

  // Token verification.
  if($row[0][0] == $token) {
      $query = "UPDATE user SET password='$password' WHERE email='$email'";
      $result = mysqli_query($db->getConnection(), $query);
      echo true;
  }
  else 
    echo false;
  exit;