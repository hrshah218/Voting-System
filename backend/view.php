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
      <li class="nav-item active">
        <a class="nav-link" href="view.php">View Users and Aspirants <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="session.php">Session</a>
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
<h2>View Tables in Database</h2>


    <form method="post">

      <button type="submit" name="aspirants" class="btn btn-primary btn-lg">View Aspirants</button>

      <?php
      if (array_key_exists("aspirants", $_POST)){

        $result = $db->readAspirants();

        $rows = $result->num_rows;

        if ($rows == 0){
          //aspirants table is empty
          $error = "The Aspirants Table is Empty! Please Populate the Table!";
        }else {
          //further processing
          echo '<table class="table table-striped" style="margin-top:30px">';
          echo '<thead class="thead-dark">';
          echo '<tr>';
          echo '<th scope="col">Image</th>';
          echo '<th scope="col">Position</th>';
          echo '<th scope="col">ID Number</th>';
          echo '<th scope="col">Name</th>';
          echo '<th scope="col">Agenda</th>';
          echo '</tr>';
          echo '</thead>';
          echo '<tbody>';

          while ($row = $result->fetch_assoc()) {
            echo "<tr>";

            echo "<td>";
            echo "<img src='uploads/".$row['picture']."' height='150px' width='150px' class='rounded-circle'>";
            echo "</td>";

            echo "<td>";
            echo $row['position'];
            echo "</td>";

            echo "<td>";
            echo $row['id'];
            echo "</td>";

            echo "<td>";
            echo $row['firstname']." ".$row['lastname'];
            echo "</td>";

            echo "<td>";
            echo $row['agenda'];
            echo "</td>";

            echo "</tr>";
          }

          echo '</tbody>';
          echo '</table>';
        }
      }

       ?>

      <button type="submit" name="users" class="btn btn-primary btn-lg">View Users</button>

      <?php
      if (array_key_exists("users", $_POST)){

        $result = $db->readUsers();

        $rows = $result->num_rows;

        if ($rows == 0){
          //Empty Table
          $error = "The Users Table is Empty. Please populate it!";

        }else {
          //Further processing
          echo '<table class="table table-striped" style="margin-top:30px">';
          echo '<thead class="thead-dark">';
          echo '<tr>';
          echo '<th scope="col">ID</th>';
          echo '<th scope="col">Name</th>';
          echo '<th scope="col">Email</th>';
          echo '</tr>';
          echo '</thead>';
          echo '<tbody>';

          while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";

            echo "<td>";
            echo $row['id'];
            echo "</td>";

            echo "<td>";
            echo $row['firstname']." ".$row['lastname'];
            echo "</td>";

            echo "<td>";
            echo $row['email'];
            echo "</td>";

            echo "</tr>";
          }

          echo '</tbody>';
          echo '</table>';
        }
      }

       ?>

    </form>

    <div id="error"><?php if ($error!="") {
      echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

    } ?></div>

    <div id="success"><?php if ($success!="") {
      echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

    } ?></div>

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
