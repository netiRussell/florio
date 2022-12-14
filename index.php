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
        $session_role = (isset($_SESSION['role'])) ? $_SESSION['role'] : null;
        if( $session_role ){
          if($_SESSION['role'] == 'user'){
            include 'u_home.php';
          } else if($_SESSION['role'] == 'admin'){
            include 'a_home.php';
          }
        } else {
          include 'login_page.php';
        }
      ?>

    </main>

    <div id="modal" class="modal hidden">
      <p class="modal_text text5">This is some text that will be removed</p>
      <button class="button text6 modal_close">Okay</button>
    </div>
    <div id="overlay" class="overlay hidden"></div>
    
    <script type="text/javascript">
      var session_name ='<?php echo $session_role;?>';
    </script>
    <script type="module" src="includes/js/app.js"></script>
  </body>
</html>
