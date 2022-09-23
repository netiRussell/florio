<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// $request = $_POST['request'];
$request = "show_products";

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

    $html .= "<div class='product'> <div class='w-thumbnail'> <img class='thumbnail' src='" . $row[2] . "' alt='" . $row[1] . "' /></div><p class='product_text text7'>" . $row[1] . ": " . $row[4] . "</p><div class='w-product_order'> <button class='button button_cart text7'>Order</button> <p class='product_price text7'>$" . $row[3] . "</p></div></div>";
  }
  

  echo json_encode(
    array(
      'html' => $html,
    )
  );
}