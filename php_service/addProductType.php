<?php


header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token ,Authorization, Token, token, TOKEN');
  include("functions.php");
  if(isset($_POST['category_name'])){
    $name = $_POST['category_name'];
    echo addCategory($name);
  }


 ?>
