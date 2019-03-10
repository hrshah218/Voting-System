<?php

session_start();

require_once '../OOP/Includes/DbOperations.php';

$error = "";
$success = "";
$filename = "";
$filetmpname = "";
$folder = 'uploads/';

if(array_key_exists("id", $_SESSION)){

  if (strpos($_SESSION['id'], "admin") !== false){

  }else {
    header("Location: index.php?logout=1");
  }

  if (array_key_exists("logout", $_POST)){

    header("Location: index.php?logout=1");
  }

  if (array_key_exists("submit", $_POST)) {

    if (!$_FILES['picture']){

      $error .= "A profile Picture is required! <br>";

    }else{

      $check = getimagesize($_FILES["picture"]["tmp_name"]);
      if($check !== false) {

        $filetmpname = $_FILES['picture']['tmp_name'];

        $target_file = $folder . basename($_FILES["picture"]["name"]);

        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

      } else {
          $error .= "The File selected is not an image! <br>";
      }
    }

    if (!$_POST['id']){

      $error .= "The ID Number is required! <br>";

    }

    if (!$_POST['firstname']){

      $error .= "First Name is required! <br>";

    }

    if (!$_POST['lastname']){

      $error .= "Last Name is required! <br>";

    }

    if (!$_POST['position']){

      $error .= "The Position is required! <br>";

    }

    if (!$_POST['agenda']){

      $error .= "The Aspirants Agenda is required! <br>";

    }

    if ($error != ""){

      $error = "<p>There were error(s) in your form:</p>".$error."";

    }else{

      $db = new DbOperations();

      $filename = $_POST['id'].".".$imageFileType;

      $result = $db->addAspirant($_POST['id'], $_POST['firstname'], $_POST['lastname'], $_POST['position'], $_POST['agenda'], $filename);

      if ($result == 0){
        //Aspirant by that ID already exists
        $error = "<p>That Aspirant already exists</p>";

      }elseif ($result == 1) {
        // successfully added Aspirant
        move_uploaded_file($filetmpname, $folder.$_POST['id'].".".$imageFileType);

        $success = "<p>You have successfully created the Aspirant</p>";

      }elseif ($result == 2) {
        // Failed to add Aspirant
        $error = "<p>Could not add Aspirant - please try again later.</p>";

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
       <li class="nav-item active">
         <a class="nav-link" href="aspirants.php">Aspirants  <span class="sr-only">(current)</span></a>
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
      <h2>Create Aspirants</h2>

      <div id="error"><?php if ($error!="") {
        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

      } ?></div>

      <div id="success"><?php if ($success!="") {
        echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

      } ?></div>

      <form method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="picture">Upload Picture</label>
          <input type="file" class="form-control" name="picture">
        </div>

        <div class="form-group">
          <label for="id">Aspirant ID Number</label>
          <input type="number" class="form-control" id="id" placeholder="Enter ID Number" name="id" autocomplete="off">
        </div>

        <div class="form-group">
          <label for="first_name">Aspirant First Name</label>
          <input type="text" class="form-control" id="first_name" placeholder="Enter First Name" name="firstname" autocomplete="off">
        </div>

        <div class="form-group">
          <label for="last_name">Aspirant Last Name</label>
          <input type="text" class="form-control" id="last_name" placeholder="Enter Last Name" name="lastname" autocomplete="off">
        </div>

        <div class="form-group">
          <label for="first_name">Position Title</label>
          <select class="form-control" name="position" id="position">
            <option value="">Select...</option>
            <?php
            $db = new DbOperations();

            $result = $db->readPositions();

            while ($row = $result->fetch_assoc()) {
              echo "<option value='".$row[position]."'>" .$row[position]."</option>";
            }
              ?>
          </select>
        </div>

        <div class="form-group">
          <label for="last_name">Aspirants Agenda</label>
          <textarea name="agenda" rows="8" cols="80"></textarea>
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
