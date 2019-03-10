<?php

session_start();

require_once '../OOP/Includes/DbOperations.php';

$db = new DbOperations();

$error = "";
$success = "";
$btnname = "";

if(array_key_exists("id", $_SESSION)){

  if (strpos($_SESSION['id'], "admin") !== false){

  }else {
    header("Location: index.php?logout=1");
  }

  if (array_key_exists("logout", $_POST)){

    header("Location: index.php?logout=1");
  }else {

    $result = $db->isSessionOpen();

    if ($result == 0){
      //Session is open
      $btnname = "<button type='submit' name='stop' class='btn btn-secondary btn-lg btn-block'>Stop Session</button>";

    }elseif ($result == 1) {
      //Session is Closed
      $btnname = "<button type='submit' name='start' class='btn btn-primary btn-lg btn-block'>Start Session</button>";

    }elseif ($result == 2) {
      //MSomething wrong at the database
      $error = "something is wrong";

    }

    if (array_key_exists("start", $_POST)){
      //start the session here
      $result = $db->startSession();

      if ($result == 0){
        //successfull
        $success = "<p>Session Started Successfully</p>";

        $btnname = "<button type='submit' name='stop' class='btn btn-secondary btn-lg btn-block'>Stop Session</button>";

      }elseif ($result == 1) {
        // Failed to Start
        $error = "Try again Later";

      }elseif ($result == 2) {
        //Something is wrong
        $error = "something is wrong";

      }
    }

    if (array_key_exists("stop", $_POST)){
      //start the session here
      $result = $db->stopSession();

      if ($result == 0){
        //successfull
        $success = "<p>Session Closed Successfully</p>";

        $btnname = "<button type='submit' name='start' class='btn btn-primary btn-lg btn-block'>Start Session</button>";

      }elseif ($result == 1) {
        // Failed to Start
        $error = "Try again Later";

      }elseif ($result == 2) {
        //Something is wrong
        $error = "something is wrong";

      }
    }
  }

} else {
  header("Location: index.php");
}

 ?>


<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <style type="text/css">

    html {
      background-color: #E6EEFF;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
        }
    body {

      background: none;

    }


    </style>

    <title>Voting System</title>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="adminloggedin.php">Overview</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="positions.php">Positions</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="aspirants.php">Aspirants</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="insertusers.php">Users</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="delete.php">Delete</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="view.php">View Users and Aspirants</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="session.php">Session <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="addrep.php">EC Rep</a>
      </li>
    </ul>

    <form class="form-inline my-2 my-lg-0" method="post">
      <button type="submit" name="logout" class="btn btn-light" style="background-color: #e3f2fd;">Logout</button>
    </form>

  </div>
</nav>

<div class="container">

<h1>Online Voting System</h1>
<h2>Set Sessions</h2>

    <div id="error"><?php if ($error!="") {
      echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

    } ?></div>

    <div id="success"><?php if ($success!="") {
      echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

    } ?></div>

    <form method="post">

      <?php echo $btnname; ?>

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
