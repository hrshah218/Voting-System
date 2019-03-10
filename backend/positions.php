<?php

session_start();

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

    if (array_key_exists("submit", $_POST)) {

      $link = mysqli_connect("shareddb1e.hosting.stackcp.net", "voting-363716d0", "y6kqj1lgog", "voting-363716d0");

      if (mysqli_connect_error()) {

      die("Database Connection Error");

    }

    if (!$_POST['name']){

      $error .= "The Position Title is required! <br>";

    }

    if ($error != ""){

      $error = "<p>There were error(s) in your form:</p>".$error."";

    }else{

      $query = "SELECT position FROM `positions` WHERE position = '".mysqli_real_escape_string($link, $_POST['name'])."' LIMIT 1";

      $result = mysqli_query($link, $query);

      if (mysqli_num_rows($result) > 0) {

          $error = "<p>That Position already exists</p>";

      }else{

        $query = "INSERT INTO `positions` (`position`)
        VALUES ('".mysqli_real_escape_string($link, $_POST['name'])."'
      )";

        if (!mysqli_query($link, $query)) {

            $error = "<p>Could not Create the Position</p>";

        }else{
          $success = "<p>You have successfully created the Position</p>";
        }

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
   <a class="navbar-brand" href="adminloggedin.php">Overview</a>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
   </button>

   <div class="collapse navbar-collapse" id="navbarSupportedContent">
     <ul class="navbar-nav mr-auto">
       <li class="nav-item active">
         <a class="nav-link" href="positions.php">Positions <span class="sr-only">(current)</span></a>
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
          <label for="name">Position Name</label>
          <input type="text" class="form-control" id="name" placeholder="Enter Position Name" name="name" autocomplete="off">
        <!--  <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
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
