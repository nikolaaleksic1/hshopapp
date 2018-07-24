<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Token, token, TOKEN');
include("functions.php");

if(isset($_POST['product_name']) &&
   isset($_POST['product_price']) &&
   isset($_POST['product_id']) &&
   isset($_POST['firstname']) &&
   isset($_POST['lastname']) &&
   isset($_POST['address']) &&
   isset($_POST['user_id'])){

    echo $productName = $_POST['product_name'];
    echo $productPrice = $_POST['product_price'];
    echo $id = $_POST['product_id'];
    echo $firstName = $_POST['firstname'];
    echo $lastName = $_POST['lastname'];
    echo $address = $_POST['address'];
    echo $user_id = $_POST['user_id'];

  echo addToCart($firstName, $lastName, $address, $productName, $productPrice, $id, $user_id);


}




?>
