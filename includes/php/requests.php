<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$request = isset($_POST['request']) ? $_POST['request'] : null;
// $request = "get_products_byname_cart";
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;


$searchbar_html = "<div class='w-search_bar'> <input type='text' class='search_bar text_input text6' id='seacrh_bar' placeholder='Search...' /> <span id='magnify_glass' class='magnify_glass'></span> </div>";

function failure(){
  echo json_encode(
    array(
      'status' => false,
    )
  );
}

function transform_price($price){
  $price = number_format((float)$price, 2, '.', '');
  if(!strpos(strval($price), '.')){
    $price .= ".00";
  }

  return $price;
}


if($role == "user"){

  function form_html_products($result){
    $html = null;
    while($row = mysqli_fetch_row($result)){
      $row[3] = transform_price($row[3]);
  
      $html .= "<div id='".$row[0]."' class='product'> <div class='w-thumbnail'> <img class='thumbnail' src='" . $row[2] . "' alt='" . $row[1] . "' /></div><p class='product_text text7'>" . $row[1] . ": " . $row[4] . "</p><div class='w-product_order'> <button class='button button_cart text7'>Order</button> <p class='product_price text7'>$" . $row[3] . "</p></div></div>";
    }
    return $html;
  }

  function form_html_orders($result){

    $html = "<div class='content y_scroll'> <table class='table'> <tr class='text7'> <th>#</th> <th>Order status</th> <th>Price</th> <th>Delivery address</th> <th>Scheduled for</th> </tr>";

    while( $row = mysqli_fetch_row($result) ){
      if(strcmp($row[1],'In cart') !== 0){

        $row[2] = transform_price($row[2]);

        $html .= "<tr class='text7'> <td>".$row[0]."</td> <td>".$row[1]."</td> <td>$".$row[2]."</td> <td>".$row[3]."</td> <td>".$row[4]."</td> </tr>";
      }
    }

    $html .= "</table></div>";
    return $html;
  }

  function form_html_cart($conn, $result, $condition){

    $html = "<div class='content y_scroll'> <table class='table'> <tr class='text7'> <th>Thumbnail</th> <th>Name</th> <th>Price</th> <th>Delivery address</th> <th>Schedule for</th> <th></th> </tr>";
    while( $row = mysqli_fetch_row($result) ){
      if(strcmp($row[3],'In cart') == 0){
        $sql = "SELECT thumbnail, name FROM products WHERE id=" . $row[2];
        if(!empty($condition) && isset($condition)){
          $sql .= $condition;
        }
        $response = mysqli_query($conn, $sql);

        if($response->num_rows > 0){
          $response = mysqli_fetch_assoc($response);

          $row[4] = transform_price($row[4]);

          $html .= "<tr class='text7' id='".$row[0]."' > <td><img class='thumbnail_cart' src='".$response['thumbnail']."' alt='".$response['name']."' /></td> <td>".$response['name']."</td> <td>$".$row[4]."</td> <td><input type='text' data-func='delivery_address_func' class='input_cart text_input text7' placeholder='Address' /></td> <td><input type='date' data-func='delivery_date_func' class='input_cart text_input text7' placeholder='mm / dd / yyyy' /></td> <td><button class='place_order_func button button_order'>Order</button></td> </tr>";
        }
      }
    }

    $html .= "</table></div>";

    return $html;
  }
  
  if( $request == "show_products" ){
    include_once 'dbh_inc.php';
    $sql = "SELECT id,name,thumbnail,price,description FROM products";
    $result = mysqli_query($conn, $sql);
    $html = form_html_products($result);
  
    echo json_encode(
      array(
        'html' => $html,
      )
    );
  } else if( $request == "get_product" ){

    include_once 'dbh_inc.php';
    $id = $_POST['id'];
    $sql = "SELECT * FROM products WHERE id=" . $id;
  
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
  
    $result['price'] = transform_price($result['price']);
  
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

    $sql = "SELECT price FROM products WHERE id=" . $product_id;
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));

    $price = $result['price']*$_POST['quantity'];
      
    $sql = "INSERT INTO orders ( status, quantity, delivery_address, product_id, customer_id, scheduled_for, price ) VALUES ('In cart', ".intval($quantity).",'',".intval($product_id).", ".intval($user_id).", '', ".$price.")";
    $result = mysqli_query($conn, $sql);
      
    if($result){
      echo json_encode(
        array(
          'status' => true,
        )
      );
    } else {
      failure();
    }
  
  } else if( $request == "u_show_cart"){

    $id = $_SESSION['id'];
    include_once 'dbh_inc.php';
    $sql = "SELECT id,quantity,product_id,status,price FROM orders WHERE customer_id=" . $id;
    $result = mysqli_query($conn, $sql);

    $html = $searchbar_html . form_html_cart($conn, $result, "");

    echo json_encode(
      array(
        'html' => $html,
        'status' => true,
      )
    );
    
  } else if($request == "u_place_order"){
    include_once 'dbh_inc.php';
    $customer_id = $_SESSION['id'];
    $order_id = $_POST['order_id'];
    $message = '';

    $sql = "SELECT customer_id, quantity, product_id, price FROM orders WHERE id=" . $order_id;
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    $price = $result['price'];
    $customer_id_db = $result['customer_id'];

    $sql = "SELECT account FROM user WHERE id=" . $customer_id;
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    
    if($price > $result['account']){
      $message = "Your account doesn't have enough money to place this order.";
      $status = false;
    } else if(strcmp($customer_id, $customer_id_db) !== 0){
      $message = "System error, invalid account id for the request.";
      $status = false;
    } else if(empty($_POST['address']) || empty($_POST['date'])){
      $message = "You didn't provide enough information. Make sure all the text-inputs are filled.";
      $status = false;
    } else {
      $address = $_POST['address'];
      $date = $_POST['date'];

      $sql = "UPDATE orders SET status = 'Placed', delivery_address = '".$address."', scheduled_for = '".$date."' WHERE id=" . $order_id;

      if(mysqli_query($conn, $sql)){
        $sql = "UPDATE user SET account = ".(floatval($result['account'])-floatval($price))." WHERE id=" . $customer_id;
        $status = mysqli_query($conn, $sql);
      }

    }

    echo json_encode(
      array(
        'status' => $status,
        'message' => $message
      )
    );

    

  } else if($request == "u_show_orders"){

    include_once 'dbh_inc.php';
    $id = $_SESSION['id'];
    $sql = "SELECT id,status,price,delivery_address,scheduled_for FROM orders WHERE customer_id=" . $id;
    $result = mysqli_query($conn, $sql);

    $html = $searchbar_html . form_html_orders($result);
    
    echo json_encode(
      array(
        'status' => true,
        'html' => $html
      )
    );

  } else if($request == "show_categories"){
    include_once 'dbh_inc.php';
    $sql = "SELECT * FROM categories";
    $result = mysqli_query($conn, $sql);

    $html = "<ul class='categories'>";

    while( $row = mysqli_fetch_row($result) ){
      $html .= "<li class='category regular_li text6'> <input id='".$row[0]."' type='checkbox' /> <label for='".$row[0]."'>".$row[1]."</label> </li>";   
    }

    $html .= "</ul>";

    echo json_encode(
      array(
        'status' => true,
        'html' => $html
      )
    );

  } else if($request == "get_products_byname"){
    
    include_once 'dbh_inc.php';
    $sql = "SELECT * FROM products WHERE name LIKE '%".$_POST['value']."%'";
    $result = mysqli_query($conn, $sql);

    $html = form_html_products($result);

    echo json_encode(
      array(
        'status' => true,
        'html' => $html
      )
    );
    
  } else if($request == "get_products_byname_cart"){
    
    include_once 'dbh_inc.php';
    $id = $_SESSION['id'];
    $sql = "SELECT id,quantity,product_id,status,price FROM orders WHERE customer_id=" . $id;
    $result = mysqli_query($conn, $sql);

    $html = form_html_cart($conn, $result, " AND name LIKE '%" . $_POST['value'] . "%'");

    echo json_encode(
      array(
        'status' => true,
        'html' => $html
      )
    );
    
  } else if($request == "get_products_byname_orders"){
    
    if(empty($_POST['value']) || !isset($_POST['value']) || !is_numeric($_POST['value'])){
      $_POST['value'] = 0;
    }
    include_once 'dbh_inc.php';
    $id = $_SESSION['id'];
    $sql = "SELECT id,status,price,delivery_address,scheduled_for FROM orders WHERE customer_id=". $id ." AND id = ".$_POST['value'];
    $result = mysqli_query($conn, $sql);

    $html = form_html_orders($result);

    echo json_encode(
      array(
        'status' => true,
        'html' => $html
      )
    );
    
  } else if("get_products_bycategories"){
    
    include_once 'dbh_inc.php';
    $arr = explode(",", $_POST['value']);

    $sql = "SELECT id,category_id FROM products WHERE ";

    // Rough base for the second step
    // $sql = "SELECT id,name,thumbnail,price,description FROM products WHERE ";
    // for($i = 0; $i < count($arr); $i++){
    //   $sql .= "category_id=" . $arr[$i];
    //   if(!($i+1 == count($arr))){
    //     $sql .= " OR ";
    //   }
    // }

    $result = mysqli_query($conn, $sql);

    $html = form_html_products($result);

    echo json_encode(
      array(
        'status' => true,
        'html' => $html
      )
    );
  }else {
    failure();
  }

} else if( $role == "admin" ){
  // admin
} else {

  if( $request == "init_session"){
    $_SESSION['id'] = $_POST['id'];
    $_SESSION['role'] = $_POST['role'];
    $_SESSION['name'] = $_POST['name'];

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
      $sql = "INSERT INTO user (role, name, username, password, account, orders ) VALUES ('user', '".$name."', '".$username."', '".$password_hash."', 0, '')";
  
      $result = mysqli_query($conn, $sql);
  
      if(!$result){
        $status = false;
        $message = "This username is taken / sql error";
      }
  
    }
  
    echo json_encode(
      array(
        'status' => $status,
        'message' => $message
      )
    );
  } else {
    failure();
  }

}

if( $request == "delete_session" ){
  $_SESSION = array();

  if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
      );
  }

  session_destroy();
}