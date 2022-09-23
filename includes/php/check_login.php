<?php
    // Sing up rough sketch:

    // include_once("includes/php/dbh_inc.php");
    // $password_hash = password_hash('123', PASSWORD_DEFAULT);
    // $sql = "INSERT INTO user (role, name, username, password, orders, account ) VALUES ('admin', 'Russell', 'adm3in_test', '" . $password_hash . "', '', 2000)"; // Creating a user with hashed password
    // $result = mysqli_query($conn, $sql);
    // var_dump($result);
  ?>

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
    $message .= "Username cannot be empty!";
    $ok = false;
  }

  if( !isset($_POST['password']) || empty($_POST['password']) ){
    $message .= "Password cannot be empty!";
    $ok = false;
  }

  if($ok){
    include_once 'dbh_inc.php';
    $sql = "SELECT id, role, password, name, orders, account, history FROM user WHERE username = '" . $username . "'";
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
          'account_history' => $result['history'],
        )
      );
    }
  } else{
    fail($message);
  }