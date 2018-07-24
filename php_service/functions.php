<?php

$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=hshop", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo "Connected successfully";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }



    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      die();
    }

  function checkIfLoggedIn(){
    global $conn;
    if(isset($_SERVER['HTTP_TOKEN'])){
      $token = $_SERVER['HTTP_TOKEN'];
      $result = $conn->prepare("SELECT * FROM users WHERE token=?");
      try{
        $result-> execute(array($token));
        if($result->fetchColumn() > 0){
            return true;
        }else{
            return false;
        }
      } catch (PDOException $ex) {
              echo $ex->getMessage();
      }
    }
    else{
      return false;
    }
  }


  function login($username, $password){
    global $conn;
    $rarray = array();
    if(checkLogin($username,$password)){
    $id = sha1(uniqid());
    $result2 = $conn->prepare("UPDATE users SET token=? WHERE username=?");
    $result2-> execute(array($id, $username));
    $rarray['token'] = $id;
    } else{
      header('HTTP/1.1 401 Unauthorized');
      $rarray['error'] = "Invalid username/password";
    }
      return json_encode($rarray);
  }



  function checkLogin($username, $password){
    global $conn;
    $password = md5($password);
    $result = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $result-> execute(array($username, $password));
    if($result->fetchColumn() > 0)
    {
    return true;
    }
    else{
    return false;
    }
  }





  function register($username, $password, $firstname, $lastname){
      global $conn;
      $rarray = array();
      $errors = "";
    if(checkIfUserExists($username)){
      $errors .= "Username already exists\r\n";
    }
    if(strlen($username) < 5){
      $errors .= "Username must have at least 5 characters\r\n";
    }
    if(strlen($password) < 5){
      $errors .= "Password must have at least 5 characters\r\n";
    }
    if(strlen($firstname) < 3){
      $errors .= "First name must have at least 3 characters\r\n";
    }
    if(strlen($lastname) < 3){
      $errors .= "Last name must have at least 3 characters\r\n";
    }
    if($errors == ""){
        $stmt = $conn->prepare("INSERT INTO users( first_name, last_name, username, password )  VALUES(?, ?, ?, ?)");
          try{
            $pass =md5($password);
            $stmt->execute(array( $firstname, $lastname, $username, $pass));
              $id = sha1(uniqid());
              $result2 = $conn->prepare("UPDATE users SET token=? WHERE username=?");
              $result2->execute([$id, $username]);
              $rarray['token'] = $id;
          }
          catch(PDOException $e) {
            echo $e->getMessage();
          }
      }else{
        header('HTTP/1.1 400 Bad request');
        $rarray['error'] = "Database connection error";
      }
      return json_encode($rarray);
  }


  function checkIfUserExists($username){
    global $conn;
    $result = $conn->prepare("SELECT * FROM users WHERE username=?");
    $result-> execute(array($username));
    if($result->fetchColumn() > 0)
    {
    return true;
    }
    else{
    return false;
    }
  }



  function addProduct($productName,$productPrice, $productImage, $productDesc, $productDays, $dateFrom, $dateTo, $produtTypeId){
    global $conn;
    $rarray = array();
    echo $produtTypeId;
    if(checkIfLoggedIn()){
    $stmt = $conn->prepare("INSERT INTO products(product_name, product_price, product_image, product_desc, product_days, date_from, date_to, product_type_id )  VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
      try{
        $stmt->execute(array( $productName,$productPrice, $productImage, $productDesc, $productDays, $dateFrom, $dateTo, $produtTypeId));
      }
      catch(PDOException $e) {
        echo $e->getMessage();
      }
    } else{
    $rarray['error'] = "Please log in";
    header('HTTP/1.1 401 Unauthorized');
    }
    return json_encode($rarray);
  }

  function addToCart($firstName, $lastName, $address, $productName, $productPrice, $id, $user_id){
    global $conn;
    $rarray = array();
    echo $produtTypeId;
    if(checkIfLoggedIn()){
    $stmt = $conn->prepare("INSERT INTO cart(firstname, lastname, address, product_name, product_price, product_id, user_id)  VALUES(?, ?, ?, ?, ?, ?, ?)");
      try{
        $stmt->execute(array( $firstName, $lastName, $address, $productName, $productPrice, $id, $user_id));
      }
      catch(PDOException $e) {
        echo $e->getMessage();
      }
    } else{
    $rarray['error'] = "Please log in";
    header('HTTP/1.1 401 Unauthorized');
    }
    return json_encode($rarray);
  }

  // function getProductTypes(){
  //   global $conn;
  //   $rarray = array();
  //   if(checkIfLoggedIn()){
  //     $result = $conn->query("SELECT * FROM category");
  //     $product_types = array();
  //   if($result->fetchColumn() > 0)
  //   {
  //     //$row = $sth->fetch(PDO::FETCH_ASSOC)
  //     $result2 = $conn->query("SELECT * FROM category");
  //     while($row = $result2->fetch(PDO::FETCH_ASSOC)) {
  //     $one_product = array();
  //     $one_product['id'] = $row['id'];
  //     $one_product['name'] = $row['category_name'];
  //     array_push($product_types,$one_product);
  //   }
  //   }
  //     $rarray['product_types'] = $product_types;
  //     return json_encode($rarray);
  //     } else{
  //       $rarray['error'] = "Please log in";
  //       header('HTTP/1.1 401 Unauthorized');
  //       return json_encode($rarray);
  //   }
  // }

  function getProducts(){
    global $conn;
    $rarray = array();
  //  if(checkIfLoggedIn()){
      $result = $conn->query("SELECT * FROM products");
      $products = array();
      if($result->fetchColumn() > 0)
      {
      $result2 = $conn->query("SELECT * FROM products");
      while($row = $result2->fetch(PDO::FETCH_ASSOC)) {
      $one_product = array();
      $one_product['id'] = $row['id'];
      $one_product['product_name'] = $row['product_name'];
      $one_product['product_price'] = $row['product_price'];
      $one_product['product_image'] = $row['product_image'];
      $one_product['product_type_id'] = $row['product_type_id'];
      $one_product['product_desc'] = $row['product_desc'];
      $one_product['product_days'] = $row['product_days'];
      $one_product['date_from'] = $row['date_from'];
      $one_product['date_to'] = $row['date_to'];

      array_push($products,$one_product);
      }
      }
      $rarray['products'] = $products;
      return json_encode($rarray);
    // } else{
    //   $rarray['error'] = "Please log in";
    //   header('HTTP/1.1 401 Unauthorized');
    //   return json_encode($rarray);
    // }
  }

  function deleteProduct($id){
    global $conn;
    $rarray = array();
    if(checkIfLoggedIn()){
      $result = $conn->prepare("DELETE FROM products WHERE id=?");
      $result->execute(array( $id));
      $rarray['success'] = "Deleted successfully";
    } else{
      $rarray['error'] = "Please log in";
      header('HTTP/1.1 401 Unauthorized');
    }
    return json_encode($rarray);
  }

function getOneProduct($id){
  global $conn;
  $rarray = array();
//  if(checkIfLoggedIn()){
  $result = $conn->query("SELECT * FROM products WHERE id=".$id);
  $products = array();
    if($result->fetchColumn() > 0)
    {
      $result2 = $conn->query("SELECT * FROM products WHERE id=" .$id);
      while($row = $result2->fetch(PDO::FETCH_ASSOC)) {
      $one_product = array();
      $one_product['id'] = $row['id'];
      $one_product['product_name'] = $row['product_name'];
      $one_product['product_price'] = $row['product_price'];
      $one_product['product_image'] = $row['product_image'];
      $one_product['product_type_id'] = $row['product_type_id'];
      $one_product['product_desc'] = $row['product_desc'];
      $one_product['product_days'] = $row['product_days'];
      $one_product['date_from'] = $row['date_from'];
      $one_product['date_to'] = $row['date_to'];
      $products = $one_product;
    }
}
  $rarray['data'] = $products;
  return json_encode($rarray);
  // } else{
  //   $rarray['error'] = "Please log in";
  //   header('HTTP/1.1 401 Unauthorized');
  //   return json_encode($rarray);
  // }
}




function updateProduct($productName,$productPrice, $productImage, $productDesc, $productDays, $dateFrom, $dateTo, $produtTypeId, $id){
  global $conn;
  $rarray = array();
 if(checkIfLoggedIn()){
    $sql = "UPDATE products SET product_name =?, product_price =?, product_image =?,product_desc=?,product_days=?,  date_from=?, date_to=?, product_type_id =?
    WHERE id=?";
    $stmt= $conn->prepare($sql);
    $stmt->execute([$productName,$productPrice, $productImage, $productDesc, $productDays, $dateFrom, $dateTo, $produtTypeId, $id]);

  if($stmt->execute()){
  $rarray['success'] = "updated";
  }else{
  $rarray['error'] = "Database connection error";
  }
   } else{
    $rarray['error'] = "Please log in";
    header('HTTP/1.1 401 Unauthorized');
   }
  return json_encode($rarray);
}


function addCategory($name){
  global $conn;
  $rarray = array();
  if(checkIfLoggedIn()){
    $stmt = $conn->prepare("INSERT INTO category (category_name) VALUES (?)");
    $stmt->execute(array( $name));
  } else{
    $rarray['error'] = "Please log in";
    header('HTTP/1.1 401 Unauthorized');
  }
  return json_encode($rarray);
}


function getProductTypes(){
  global $conn;
  $rarray = array();
//  if(checkIfLoggedIn()){
    $result = $conn->query("SELECT * FROM category");
    $product_types = array();
  if($result->fetchColumn() > 0)
  {
    //$row = $sth->fetch(PDO::FETCH_ASSOC)
    $result2 = $conn->query("SELECT * FROM category");
    while($row = $result2->fetch(PDO::FETCH_ASSOC)) {
    $one_type = array();
    $one_type['id'] = $row['id'];
    $one_type['category_name'] = $row['category_name'];
    array_push($product_types,$one_type);
  }
  }
    $rarray['product_types'] = $product_types;
    return json_encode($rarray);
  //   } else{
  //     $rarray['error'] = "Please log in";
  //     header('HTTP/1.1 401 Unauthorized');
  //     return json_encode($rarray);
  // }
}


function deleteProductType($id){
    global $conn;
    $rarray = array();
  if(checkIfLoggedIn()){
    $result = $conn->prepare("DELETE FROM category WHERE id=?");
    $result->execute(array($id));
    $rarray['success'] = "Deleted successfully";
  } else{
    $rarray['error'] = "Please log in";
    header('HTTP/1.1 401 Unauthorized');
  }
    return json_encode($rarray);
}




function getUserId($token){
  global $conn;
// $token = $_SERVER['HTTP_TOKEN'];
    $rarray = array();
    $result = $conn->query( "SELECT * FROM users WHERE token = '$token' " );
   //$result-> execute(array($token));
    $user_id = array();
  if($result->fetchColumn() > 0)
  {


    $result2 = $conn->query("SELECT * FROM users WHERE token = '$token'");
    $result2-> execute(array($token));
    while($row = $result2->fetch(PDO::FETCH_ASSOC)) {
    $one_id = array();
    $one_id['id'] = $row['id'];
    array_push($user_id,$one_id);
  }
  }
    $rarray['user_id'] = $user_id;
    return json_encode($rarray);

}



function getCarts($id){
  global $conn;
// $token = $_SERVER['HTTP_TOKEN'];
    $rarray = array();
    $result = $conn->query( "SELECT * FROM cart WHERE user_id = '$id' " );
   //$result-> execute(array($token));
    $user_id = array();
  if($result->fetchColumn() > 0)
  {


    $result2 = $conn->query("SELECT * FROM cart WHERE user_id = '$id' ");
    $result2-> execute(array($id));
    while($row = $result2->fetch(PDO::FETCH_ASSOC)) {
    $one_cart = array();
    $one_cart['id'] = $row['id'];
    $one_cart['product_name'] = $row['product_name'];
    $one_cart['product_price'] = $row['product_price'];
    $one_cart['firstname'] = $row['firstname'];
    $one_cart['lastname'] = $row['lastname'];
    $one_cart['address'] = $row['address'];
    $one_cart['product_id'] = $row['product_id'];


    array_push($user_id,$one_cart);
  }
  }
    $rarray['carts'] = $user_id;
    return json_encode($rarray);

}


?>
