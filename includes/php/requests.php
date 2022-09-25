<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$request = $_POST['request'];
// $request = "product_to_cart";

function failure(){
  echo json_encode(
    array(
      'status' => false,
    )
  );
}


if( $request == "init_session"){
  $_SESSION['id'] = $_POST['id'];
  $_SESSION['name'] = $_POST['name'];
} else if( $request === "delete_session" ){
  $_SESSION = array();

  if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
      );
  }

  session_destroy();
} else if( $request == "show_products" ){
  include_once 'dbh_inc.php';
  $sql = "SELECT * FROM products";
  $result = mysqli_query($conn, $sql);
  $html = null;

  while($row = mysqli_fetch_row($result)){
    if(!strpos(strval($row[3]), '.')){
      $row[3] .= ".00";
    }

    $html .= "<div id='".$row[0]."' class='product'> <div class='w-thumbnail'> <img class='thumbnail' src='" . $row[2] . "' alt='" . $row[1] . "' /></div><p class='product_text text7'>" . $row[1] . ": " . $row[4] . "</p><div class='w-product_order'> <button class='button button_cart text7'>Order</button> <p class='product_price text7'>$" . $row[3] . "</p></div></div>";
  }
  

  echo json_encode(
    array(
      'html' => $html,
    )
  );
} else if( $request == 'sign_up'){
  $status = true;
  $message = "";

  $name = isset($_POST['name']) ? $_POST['name'] : '';
  $username = isset($_POST['username']) ? $_POST['username'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  if( (!isset($_POST['username']) || empty($_POST['username'])) || (!isset($_POST['name']) || empty($_POST['name'])) || (!isset($_POST['password']) || empty($_POST['password']))){
    $message = "One of the text fields is empty.";
    $status = false;
  }

  if($status){

    include_once 'dbh_inc.php';
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO user (role, name, username, password, cart, account, orders ) VALUES ('user', '".$name."', '".$username."', '".$password_hash."', '', 0, '')";

    $result = mysqli_query($conn, $sql);

    if(!$result){
      $status = false;
      $message = "Server error(sql / connection)";
    }

  }

  echo json_encode(
    array(
      'status' => $status,
      'message' => $message
    )
  );
} else if( $request == "get_product" ){

  include_once 'dbh_inc.php';
  $id = $_POST['id'];
  $sql = "SELECT * FROM products WHERE id=" . $id;

  $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));

  
  if(!strpos(strval($result['price']), '.')){
    $result['price'] .= ".00";
  }

  $html = "<div class='content content_product'> <div id='".$result['id']."' class='w-product_info y_scroll'> <img class='thumbnail_main' src='".$result['thumbnail']."' alt='".$result['name']."' /> <div class='product_info'> <h1 class='product_name text2'>".$result['name']."</h1> <p class='path text7'><span>Products</span> / <span>".$result['name']."</span></p> <p class='product_description text6_5'> ".$result['description']." </p> </div> </div> <div class='w-product_func'> <div class='product_main_price'> <p id='product_price' class='text5'>$".$result['price']."</p> <hr class='regular_hr' /> </div> <div class='product_func'> <div class='product_num'> <button id='increase_quantity' class='text7'>+</button> <input id='quantity_value' class='text7 text_input' type='text' spellcheck='false' pattern='[0-9]+' value='1' /> <button id='decrease_quantity' class='text7'>-</button> </div> <button id='product_order' class='text6 product_order button'>Add to cart</button> </div> </div> </div>";

  echo json_encode(
    array(
      'html' => $html,
    )
  );

} else if( $request == "product_to_cart" ){

  $user_id = $_SESSION['id'];
  $product_id = $_POST['product'];
  $quantity = $_POST['quantity'];

  include_once 'dbh_inc.php';
  $sql = "SELECT cart FROM user WHERE id=" . $user_id;
  $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));

  $array = empty($result['cart']) ? array() : explode('/', $result['cart']);
  array_push($array, $product_id.",".$quantity);

  $sql = "UPDATE user SET cart = ('".implode('/', $array)."') WHERE id = ".$user_id;
  $result = mysqli_query($conn, $sql);

  if($result){
    echo json_encode(
      array(
        'status' => true,
      )
    );
  } else {
    $failure();
  }

} else {

  $failure();

}