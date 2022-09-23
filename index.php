<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <title>Florio</title>
  </head>
  <body>
    <main>
      <?php
        $session_id = (isset($_SESSION['name'])) ? $_SESSION['name'] : null;
        if( $session_id ){
          include 'u_home.php';
        } else {
          include 'login_page.php';
        }
      ?>
    </main>
    
    <script type="text/javascript">
      var session_name ='<?php echo $session_id;?>';
    </script>
    <script type="module" src="includes/js/app.js"></script>
  </body>
</html>
