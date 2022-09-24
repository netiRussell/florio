<?php

  $username = isset($_POST['username']) ? $_POST['username'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  $ok = true;
  $message = "";

  function fail($msg){
    $ok = false;
    echo json_encode(
      array(
        'ok' => $ok,
        'message' => $msg
      )
    );
  }

  if( !isset($_POST['username']) || empty($_POST['username']) ){
    $message .= "Username cannot be empty! ";
    $ok = false;
  }

  if( !isset($_POST['password']) || empty($_POST['password']) ){
    $message .= "Password cannot be empty! ";
    $ok = false;
  }

  if($ok){
    include_once 'dbh_inc.php';
    $sql = "SELECT id, role, password, name, cart, account, orders FROM user WHERE username = '" . $username . "'";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));

    if( !password_verify($password, $result['password']) ){
      $message = "Incorrect username / password";
      fail($message);
    } else {
      echo json_encode(
        array(
          'ok' => $ok,
          'id' => $result['id'],
          'role' => $result['role'],
          'name' => $result['name'],
          'orders' => $result['orders'],
          'account' => $result['account'],
          'account_history' => $result['cart'],
        )
      );
    }
  } else{
    fail($message);
  }