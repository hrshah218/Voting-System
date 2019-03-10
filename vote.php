<?php
session_start();

require_once 'OOP/Includes/DbOperations.php';

$db = new DbOperations();

$error = "";
$success = "";

//check if user has voted for that position before

function contains($text, $word){
        $found = false;
        $spaceArray = explode(' ', $text);

        $nonBreakingSpaceArray = explode(chr(160), $text);

        if (in_array($word, $spaceArray) ||
            in_array($word, $nonBreakingSpaceArray)
           ) {

            $found = true;
        }
        return $found;
     }

if (array_key_exists("logout", $_POST)){

  header("Location: index.php?logout=1");

}else {
  if(array_key_exists("id", $_SESSION)){
    if (strpos($_SESSION['id'], "admin") !== false){

      header("Location: index.php?logout=1");

    }elseif (strpos($_SESSION['id'], "rep") !== false) {

      header("Location: index.php?logout=1");

    }
  }else {
    header("Location: index.php?logout=1");
  }
}


$cat = "";
if (array_key_exists("submit", $_POST)) {

  if($_POST['category'] == ""){
    $error = "Please select a Category to Vote";
    $cat = "";
  }else {
    $cat = $_POST['category'];
  }

}

if (array_key_exists("vote", $_POST)) {

  if (!$_POST['voted']){

    $error = "Please select an Aspirant you wish to vote for";

  }else {

    if (contains($_SESSION['position'], $_POST['position'])){
       $error = 'You have already Voted for that position';
     }else {
       $result = $db->userVote($_POST['voted'], $_SESSION['id']);

       if ($result == 0){
         //vote successfull
         $success = "You have voted for the {$_POST['position']}";
         $_SESSION['position'] .=", ".$_POST['position'];

       }elseif ($result == 1) {
         // Error in Voting
         $error = "Could not Vote - Please try again later";

       }elseif ($result == 2) {
         //The user or Aspirant ID is false
         header("Location: index.php?logout=1");
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

    <div class="col d">
      SHORT SURVEY
    </div>

    <div class="col d act">
      VOTE
    </div>

    <div class="col d">
      <form class="" method="post">
        <button type="submit" class="col d btn" name="logout">LOGOUT</button>
      </form>
    </div>

  </div>
  <br>
<h4>Vote for the aspirants of your Choice:</h4>

      <div id="error"><?php if ($error!="") {
        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

      } ?></div>

      <div id="success"><?php if ($success!="") {
        echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

      } ?></div>

      <form method="post">

        <div class="row">
          <div class="col-sm">

          </div>
          <div class="col-sm">
            <label for="category">Select which Category you want to Vote for!</label>
            <select class="form-control" name="category" id="category">
              <option value="">Select...</option>
              <?php
              $result = $db->readPositions();

              while ($row = $result->fetch_assoc()) {
                echo "<option value='".$row['position']."'>" .$row['position']."</option>";
              }
                ?>
            </select>

            <br>

            <button type="submit" name="submit" class="btn btn-outline-dark">GO!</button>
          </div>
          <div class="col-sm">

          </div>
        </div>


<?php

if(!$cat == ""){
  $result = $db->readAspirantsbyPosition($cat);
  $rows = $result->num_rows;
  if ($rows == 0){
    //There are no Aspirants in that Category
    echo "<table>";
    echo "There is no Content to Display";
    echo "</table>";
  }else {
    //Displaying the Aspirants
    echo '<table class="table table-striped" style="margin-top:30px">';
    echo '<thead class="thead-dark">';
    echo '<tr>';
    echo '<th scope="col">Image</th>';
    echo '<th scope="col">Name</th>';
    echo '<th scope="col">Agenda</th>';
    echo '<th scope="col"></th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
      echo "<tr>";

      echo "<td>";
      echo "<img src='backend/uploads/".$row['picture']."' height='150px' width='150px'>";
      echo "</td>";

      echo "<td>";
      echo $row['firstname']." ".$row['lastname'];
      echo "</td>";

      echo "<td>";
      echo $row['agenda'];
      echo "</td>";

      echo "<td>";
      echo "<input type='radio' name='voted' value='".$row['id']."'> Vote";
      echo "<input type='hidden' name='position' value='".$row['position']."'>";
      echo "</td>";

      echo "</tr>";
    }

    echo "</table>";
    echo "<button type='submit' name='vote' class='btn btn-outline-dark' style='margin-top:30px'>Vote!</button>";

  }


}

 ?>
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
