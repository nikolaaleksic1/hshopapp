<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST');
include("functions.php");

if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['username']) && isset($_POST['password'])){
  $firstname = $_POST['first_name'];
  $lastname = $_POST['last_name'];
  $username = $_POST['username'];
  $password = $_POST['password'];
echo register($username,$password,$firstname,$lastname);
}
 ?>