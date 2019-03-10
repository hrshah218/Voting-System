<?php

session_start();

require_once 'OOP/Includes/DbOperations.php';

$error = "";
$success = "";

if (array_key_exists("logout", $_GET)){
  unset($_SESSION['activate']);
  unset($_SESSION['id']);
  unset($_SESSION['position']);
  session_destroy();
  header("Location: index.php");
}else {

if (array_key_exists("submit", $_POST)) {

  if (!$_POST['id']){

    $error .= "Please Enter your ID Number to proceed";

  }else{

    $db = new DbOperations();

    //check to see if Session is Open
    $session = $db->isSessionOpen();

    if($session == 0){
      //session is open
      $result = $db->userLogin($_POST['id']);

      if($result['error']){
        //Failed
        $error = $result['message'];
      }else {
        //That ID exists
        if($result['vote'] == 1){
          //user has already voted
          $error = "You have already voted";

        }elseif ($result['vote'] == 0){
          //The user has not Voted
          //user can proceed to vote
          //creating session ID
          $_SESSION['id'] = $_POST['id'];

          $_SESSION['position'] = "";

          if($result['verify'] == 0){
            //create 2FA code
            $mail = $result['email'];

            $generate = mt_rand(100000,999999);

            $to = $mail;

            $subject = "Authentication Code";

            $txt = "Use this code to verify yourself: {$generate}";

            $_SESSION['activate'] = $generate;

            $headers = "From: votingsystem@hrshah.com";

            $function_mail = mail($to, $subject, $txt, $headers);

            if ($function_mail == true) {

              $success = "The Activation Code has been sent to your mail. Please enter the code to Proceed";
              header("Location: verify.php");

            }else{
              //Verification 2FA code failed
              $error = "2: Try again Later";
            }

          }elseif ($result['verify'] == 1) {
            //User has verified himself already
            //check to see if user has updated details
            if ($result['updated'] == 0) {
              //user has not updated details
              header("Location: moreinfo.php");

            }elseif ($result['updated'] == 1) {
              //user has updated details
              header("Location: vote.php");

            }else {
              //Something went Wrong here; Updated should either be 1 or 0
              $error = "3: Try again Later";
            }
          }
        }
      }

    }elseif ($session == 1) {
      //Session is Closed
      $error = "Voting Session is closed. Please Try again later.";

    }elseif ($session == 2) {
      //Something is wrong, contact Admin; Session only be one row in the database
      $error = "6: Try again Later";

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

    .d {
      background-color: black;
      margin-right: 20px;
      margin: 5px;
      padding: 10px;
      text-align: center;
      font-family: cursive;
      color: gold;
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

      <h1>Voting System</h1>
      <br>
      <div class="row">

        <div class="col">

          <h4> <img src="images/download.png" alt="" style="height:100px; width:170px;"> Student Association Council Elections</h4>
        </div>

      </div>

      <br>


        <h5 style="color:#0069D9;">Please follow the Steps below: </h5>



      <div class="row">

        <div class="col d act">
          ENTER YOUR ID NUMBER TO PROCEED
        </div>

        <div class="col d">
          VERIFY YOURSELF
        </div>

        <div class="col d">
          SHORT SURVEY
        </div>

        <div class="col d">
          VOTE
        </div>

        <div class="col d">
          LOGOUT
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
          <input type="number" class="form-control" id="id" placeholder="ID Number" name="id" autocomplete="off" required>
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
