<?php

  $username = isset($_POST['username']) ? $_POST['username'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  $ok = true;
  $message = "";

  if( !isset($_POST['username']) || empty($_POST['username']) ){
    $message .= "<p class='p-error'>Username cannot be empty!</p>";
    $ok = false;
  }

  if( !isset($_POST['password']) || empty($_POST['password']) ){
    $message .= "<p class='p-error'>Password cannot be empty!</p>";
    $ok = false;
  }

  if($ok){
    include_once 'dbh_inc.php';
    $sql = "SELECT id, password, name, orders, account, history FROM user WHERE username = '" . $username . "'";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));

    if( !password_verify($password, $result['password']) ){
      $ok = false;
      $message = "<p class='p-error'>Incorrect username / password</p>";
    } else {
      $data_id = $result['id'];
      $data_name = $result['name'];
      $data_orders = $result['orders'];
      $data_account = $result['account'];
      $data_account_history = $result['history'];
    }
  }


  echo json_encode(
    array(
      'ok' => $ok,
      'message' => $message,
      'id' => $data_id,
      'name' => $data_name,
      'orders' => $data_orders,
      'account' => $data_account,
      'account_history' => $data_account_history
    )
  );