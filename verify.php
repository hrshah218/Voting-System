<?php
session_start();

require_once 'OOP/Includes/DbOperations.php';

if (array_key_exists("logout", $_POST)){

  header("Location: index.php?logout=1");
}else {

if(array_key_exists("id", $_SESSION)){

  if (strpos($_SESSION['id'], "admin") !== false){

    header("Location: index.php?logout=1");

  }elseif (strpos($_SESSION['id'], "rep") !== false) {

    header("Location: index.php?logout=1");

  }

  $error = "";
  $success = "";
  $mail = "";

  function obfuscate_email($email)
  {
      $em   = explode("@",$email);
      $name = implode(array_slice($em, 0, count($em)-1), '@');
      $len  = floor(strlen($name)/2);

      return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
  }

  $db = new DbOperations();

  $result = $db->readSpecificUsers($_SESSION['id']);

  if($result == 0){
    //Something Went Wrong at the Database
    $error = "1: Try again Later";

  }elseif ($result == 1) {
    //User by this ID does not exist
    header("Location: index.php?logout=1");

  }else {
    //Fetch Email
    $mail = $result['email'];

  }

if (array_key_exists("verify", $_POST)) {

  if (!$_POST['active']){

    $error .= "Please enter a Verification Code <br>";

  }

  if ($error != ""){

    $error = "<p>There were error(s) in your form:</p>".$error."";

  }else {

    $result = $db->userVerification($_SESSION['id'], $_SESSION['activate'], $_POST['active']);

    if($result == 0){
      //verified
      $success = "You have verified yourself!";

      header("Location: moreinfo.php");

    }elseif ($result == 1) {
      //verified but update into database failed
      $success = "Verification done but...";
      $error = "Try again later";

    }elseif ($result == 2) {
      //Wrong Verification Code
      $error = "Invalid Authentication Code";

    }elseif ($result == 3) {
      //User by that ID does not exist
      $error = "Invalid User";
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

      <div class="col d act">
        VERIFY YOURSELF
      </div>

      <div class="col d">
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
      <h4>Please Verify Yourself</h4>

      <div id="error"><?php if ($error!="") {
        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

      } ?></div>

      <div id="success"><?php if ($success!="") {
        echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

      } ?></div>

      <div class="row">
        <div class="col">

      <form>

        <div class="form-group">
          <label for="id">Your Email address is: <?php echo obfuscate_email($mail); ?></label>
        </div>

      </form>

      <form method="post">
        <div class="row">
          <div class="col-sm">

          </div>
          <div class="col-sm">
            <input type="number" class="form-control" id="id" placeholder="Enter Activation Code" name="active" autocomplete="off" required>
            <br>
            <button type="submit" name="verify" class="btn btn-outline-dark">Verify</button>
          </div>
          <div class="col-sm">

          </div>
        </div>
    </form>

    </div>
    </div>
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
