<?php
  session_start();

  require_once '../OOP/Includes/DbOperations.php';

  if (array_key_exists("id", $_SESSION)){
    session_unset($_SESSION['id']);
  }

  $error = "";
  $success = "";

  if (array_key_exists("logout", $_GET)){
    session_destroy();
    header("Location: index.php");

  }else {

  if (array_key_exists("submit", $_POST)) {

    if (!$_POST['email']){

      $error .= "An email Address is required! <br>";

    }

    if (!$_POST['password']){

      $error .= "A Password is required! <br>";

    }

    if ($error != ""){

      $error = "<p>There were error(s) in your form:</p>".$error."";

    }else {

      $db = new DbOperations();

      $result = $db->adminLogin($_POST['email'], $_POST['password']);

      if ($result['error']) {
        //Credentials are Invalid
        $error = $result['message'];
      }else {
        //Credentials are Valid
        if ($result['Classification'] == "rep") {

          $_SESSION['id'] = $result['id']."rep";

          header("Location: ecreploggedin.php");

        }else {

          $_SESSION['id'] = $result['id']."admin";

          header("Location: adminloggedin.php");
        }
      }
    }
  }
}


 ?>


<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <style type="text/css">

    html {
      background: url(images/wallpaper.png) no-repeat center center fixed;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
        }
    body {

      background: none;

    }

    .container {

      text-align: center;
      margin-top: 50px;

    }


    </style>

    <title>Voting System</title>
  </head>
  <body>
    <div class="container">

      <h1>Voting System Backend</h1>
      <br>
      <div class="row">

        <div class="col">

          <h4> <img src="images/download.png" alt="" style="height:100px; width:170px;"> Student Association Council Elections</h4>
        </div>

      </div>
<br>
      <div id="error"><?php if ($error!="") {
        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

      } ?></div>

      <div id="success"><?php if ($success!="") {
        echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

      } ?></div>

    <br>

      <form method="post">


        <div class="row">
          <div class="col-sm">
          </div>

          <div class="col-sm">
            <label for="Email">Email address</label>
            <input type="email" class="form-control" id="Email" aria-describedby="emailHelp" placeholder="Enter email" name="email" autocomplete="off" required>
          </div>

          <div class="col-sm">
          </div>
        </div>

        <div class="row">
          <div class="col-sm">
          </div>

          <div class="col-sm">
            <label for="Password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>

            <br>

            <button type="submit" name="submit" class="btn btn-outline-dark">Login</button>

          </div>

          <div class="col-sm">
          </div>
        </div>

      </form>

    </div>

    <!-- Optional JavaScript -->

    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
