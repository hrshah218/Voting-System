<?php
session_start();

require_once 'OOP/Includes/DbOperations.php';

$db = new DbOperations();

if (array_key_exists("logout", $_POST)){

  header("Location: index.php?logout=1");
}else {

if(array_key_exists("id", $_SESSION)){

  $error = "";
  $success = "";

  if (strpos($_SESSION['id'], "admin") !== false){

    header("Location: index.php?logout=1");

  }elseif (strpos($_SESSION['id'], "rep") !== false) {

    header("Location: index.php?logout=1");

  }

if (array_key_exists("submit", $_POST)) {

  if (!$_POST['school']){

    $error .= "Please Select a School <br>";

  }

  if (!$_POST['year']){

    $error .= "Please Select the Year you are in <br>";

  }

  if (!$_POST['gender']){

    $error .= "Please select your Gender <br>";

  }

  if ($error != ""){

    $error = "<p>There were error(s) in your form:</p>".$error."";

  }else {

    $result = $db->updateUserDetails($_POST['school'], $_POST['year'], $_POST['gender'], $_SESSION['id']);

    if($result == 0){
      //successfull $query
      $success = "<p>You have updated your details!</p>";

      header("Location: vote.php");

    }elseif ($result == 1) {
      // Database error
      $error = "<p>Couldn't update the Details</p>";

    }elseif ($result == 2) {
      //Database error
      $error = "<p>Couldn't update the Details</p>";

    }elseif ($result == 3) {
      //user does not exist
      header("Location: index.php?logout=1");

    }

}

}
}else {
  header("Location: index.php");
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

    .d {
      background-color: black;
      margin: auto;
      text-align: center;
      font-family: cursive;
      color: gold;
      align-content: center;
    }

    .act {
      background-color: black;
      color: white;
    }

    </style>

    <title>Voting System</title>
  </head>
  <body>

  <div class="container">

    <div class="row">

      <div class="col">

        <h4> <img src="images/download.png" alt="" style="height:100px; width:170px;"> Student Association Council Elections </h4>
      </div>

    </div>

    <br>
      <h5 style="color:#0069D9;">Please follow the Steps below: </h5>



    <div class="row">

      <div class="col d">
        ENTER YOUR ID NUMBER TO PROCEED
      </div>

      <div class="col d">
        VERIFY YOURSELF
      </div>

      <div class="col d act">
        SHORT SURVEY
      </div>

      <div class="col d">
        VOTE
      </div>

      <div class="col d">
        <form class="" method="post">
          <button type="submit" class="col d btn" name="logout">LOGOUT</button>
        </form>
      </div>

    </div>
    <br>
  <h4>Please Complete the Survey</h4>

  <div id="error"><?php if ($error!="") {
    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

  } ?></div>

  <div id="success"><?php if ($success!="") {
    echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

  } ?></div>

  <form method="post">
    <div class="row">
      <div class="col-sm"></div>

      <div class="col-sm">
        <label for="school">Please select your school</label>
        <select class="form-control" name="school" id="school">
          <option value="">Select...</option>
          <?php
          $result = $db->readSchools();

          while ($row = $result->fetch_assoc()) {
            echo "<option value='".$row['school']."'>" .$row['school']."</option>";
          }
            ?>
        </select>
      </div>

      <div class="col-sm"></div>
    </div>

    <div class="row">
      <div class="col-sm"></div>

      <div class="col-sm">
        <label for="year">Which Year are you in?</label>
        <select class="form-control" name="year">

          <option value="">Select...</option>
          <option value="1">Freshman (1st Year)</option>
          <option value="2">Sophomore (2nd Year)</option>
          <option value="3">Junior (3rd Year)</option>
          <option value="4">Senior (4th Year)</option>

        </select>
      </div>

      <div class="col-sm"></div>
    </div>

    <div class="row">
      <div class="col-sm"></div>

      <div class="col-sm">
        <label for="gender">Select your Gender</label>
        <select class="form-control" name="gender">
          <option value="">Select...</option>
          <option value="M">Male</option>
          <option value="F">Female</option>
        </select>

        <br>
        <button type="submit" name="submit" class="btn btn-outline-dark">Update Info</button>
      </div>

      <div class="col-sm"></div>
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
