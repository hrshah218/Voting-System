<?php

session_start();

require_once '../OOP/Includes/DbOperations.php';

$error = "";
$success = "";

if(array_key_exists("id", $_SESSION)){

  if (strpos($_SESSION['id'], "admin") !== false){

  }else {
    header("Location: index.php?logout=1");
  }

  if (array_key_exists("logout", $_POST)){

    header("Location: index.php?logout=1");
  }else {

    if (array_key_exists("submit", $_POST)){

    if (!$_POST['id']){

      $error .= "The ID Number of the EC Rep is required <br>";

    }

    if (!$_POST['firstname']){

      $error .= "The Firstname of the EC Rep is required <br>";

    }

    if (!$_POST['lastname']){

      $error .= "The Lastname of the EC Rep is required <br>";

    }

    if (!$_POST['email']){

      $error .= "The Email of the EC Rep is required <br>";

    }

    if (!$_POST['password1'] && !$_POST['password2']){

      $error .= "The Password of the EC Rep is required <br>";

    }

    if ($_POST['password1'] != $_POST['password2']) {

      $error .= "The Passwords don't match <br>";

    }

    if ($_POST['role'] == ""){

      $error .= "The role is required <br>";

    }

    if ($error != ""){

      $error = "<p>There were error(s) in your form:</p>".$error."";

    }else {

      $db = new DbOperations();

      $result = $db->addRep($_POST['id'], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password1'], $_POST['role']);

      if ($result == 0){
        //Admin by that ID already exists
        $error = "<p>That Admin already exists</p>";

      }elseif ($result == 1) {
        //Added successfully
        $success = "<p>You have successfully created the Admin</p>";

      }elseif ($result == 2) {
        //Database error
        $error = "<p>Could not sign you up - please try again later.</p>";
      }
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
  <a class="navbar-brand" href="adminloggedin.php">Overview <span class="sr-only">(current)</span></a>
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
      <li class="nav-item">
        <a class="nav-link" href="session.php">Session</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="addrep.php">EC Rep <span class="sr-only">(current)</span></a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0" method="post">
      <button type="submit" name="logout" class="btn btn-light" style="background-color: #e3f2fd;">Logout</button>
    </form>
  </div>
</nav>

<div class="container">

  <h1>Online Voting System</h1>
  <h2>Create Positions</h2>

  <div id="error"><?php if ($error!="") {
    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

  } ?></div>

  <div id="success"><?php if ($success!="") {
    echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

  } ?></div>

  <form method="post">

    <div class="form-group">
      <label for="id">EC Rep ID Number</label>
      <input type="number" class="form-control" id="id" placeholder="Enter ID Number" name="id" autocomplete="off" required>
    </div>

    <div class="form-group">
      <label for="first_name">EC Rep First Name</label>
      <input type="text" class="form-control" id="first_name" placeholder="Enter First Name" name="firstname" autocomplete="off" required>
    </div>

    <div class="form-group">
      <label for="last_name">EC Rep Last Name</label>
      <input type="text" class="form-control" id="last_name" placeholder="Enter Last Name" name="lastname" autocomplete="off" required>
    </div>

    <div class="form-group">
      <label for="last_name">EC Rep Username</label>
      <input type="email" class="form-control" id="last_name" placeholder="Enter Email" name="email" autocomplete="off" required>
    </div>

    <div class="form-group">
      <label for="last_name">Password</label>
      <small id="passwordRequirement" class="form-text text-muted">The password should include UpperCase, LowerCase, Number/SpecialChar and min 8 Chars</small>
      <input type="password" class="form-control" id="last_name" placeholder="Password" name="password1" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required>
    </div>

    <div class="form-group">
      <label for="last_name">Repeat Password</label>
      <input type="password" class="form-control" id="last_name" placeholder="Enter Password again" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" name="password2" required>
    </div>

    <div class="form-group">
      <label for="first_name">Role</label>
      <select class="form-control" name="role" id="position">
        <option value="">Select...</option>
        <option value="rep">EC Rep</option>
      </select>
    </div>

    <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Create</button>
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
