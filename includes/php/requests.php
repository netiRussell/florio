<?php

session_start();
$request = $_POST['request'];

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
}