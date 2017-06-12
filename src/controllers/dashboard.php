<?php
  require_once("connection.php");

  $db = new Connect;
  $query = "SELECT * FROM user";
  $result = mysqli_query($db->getConnection(), $query);

  echo json_encode(mysqli_fetch_all($result));
?>