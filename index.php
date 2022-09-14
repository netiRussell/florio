<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>Florio</title>
</head>
<body>
  
  <?php
    // Sing up rough sketch:

    // include_once("includes/php/dbh_inc.php");
    // $password_hash = password_hash('123', PASSWORD_DEFAULT);
    // $sql = "INSERT INTO user (role, name, username, password, orders, account ) VALUES ('admin', 'Russell', 'adm3in_test', '" . $password_hash . "', '', 2000)"; // Creating a user with hashed password
    // $result = mysqli_query($conn, $sql);
    // var_dump($result);
  ?>

  <div class="w-log_in">
    <div class="error_message"></div>

    <label for="username">Username</label>
    <input type="text" id="username" spellcheck="false">

    <label for="password">Password</label>
    <input type="password" id="password">

    <button type="submit" id="login_button">Dive into the flower paradise</button>
  </div> 
  
  <script type="module" src="includes/js/app.js"></script>
</body>
</html>