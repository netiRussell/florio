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
    $sql = "SELECT password, name FROM user WHERE username = '" . $username . "'";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));

    if( $password !== $result['password'] ){
      $ok = false;
      $message .= "<p class='p-error'>Incorrect username / password</p>";
    } else {
      $data_name = $result['name'];
      $message = "<p class='p-success'>Succesful login!</p>";
    }
  }


  echo json_encode(
    array(
      'ok' => $ok,
      'message' => $message,
      'name' => $result['name']
    )
  );