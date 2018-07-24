<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token ,Authorization, Token, token, TOKEN');

include("functions.php");

if(isset($_POST['product_name']) &&
   isset($_POST['product_price']) &&
   isset($_POST['product_image'])  &&
   isset($_POST['product_type_id'])&&
   isset($_POST['id']))
   {


     echo $productName = $_POST['product_name'];
     echo $productPrice = $_POST['product_price'];
     echo $productImage = $_POST['product_image'];
     echo $produtTypeId = $_POST['product_type_id'];
     echo $productDesc = $_POST['product_desc'];
     echo $productDays = $_POST['product_days'];
     echo $dateFrom = $_POST['date_from'];
     echo $dateTo = $_POST['date_to'];
     echo $id = $_POST['id'];



echo updateProduct($productName,$productPrice, $productImage, $productDesc, $productDays, $dateFrom, $dateTo, $produtTypeId, $id);
}


// updateProduct("Spinach", "3.12", "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRNzBHYPU1HmBuDJVcH2oW8WAyqKg88PFazRwfS4CmbEzAIhTCq", "asd","5", "", "", "", 1);


 ?>
