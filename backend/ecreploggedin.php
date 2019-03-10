<?php

session_start();

require_once '../OOP/Includes/DbOperations.php';

 include_once "Venue.php";

 $all = (new Vote())->getVotes();

  $error = "";
  $success = "";
  $show = "0";


if(array_key_exists("id", $_SESSION)){

  if (strpos($_SESSION['id'], "rep") !== false){

  }else {
    header("Location: index.php?logout=1");
  }

  if (array_key_exists("logout", $_POST)){

    header("Location: index.php?logout=1");
  }else {

    // Function to get number of female students who have voted

    $voted = array_filter($all, function($vote){
              return $vote["vote"] == "1" ;
        });

    $notVoted = array_filter($all, function($vote){
              return $vote["vote"] == "0" ;
        });

    $girls = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['gender'] == 'F' ;
        });


    $boys = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['gender'] == 'M' ;
        });


    $year_1 = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['year'] == '1' ;
        });

    $year_2 = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['year'] == '2' ;
        });

    $year_3 = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['year'] == '3' ;
        });

    $year_4 = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['year'] == '4' ;
        });

    $sst = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Science and Technology' ;
        });

    $sstM = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Science and Technology' && $vote['gender'] == 'M' ;
        });

    $sstF = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Science and Technology' && $vote['gender'] == 'F' ;
        });

    $csb = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'Chandaria School of Business' ;
        });

    $csbM = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'Chandaria School of Business' && $vote['gender'] == 'M' ;
        });

    $csbF = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'Chandaria School of Business' && $vote['gender'] == 'F' ;
        });

    $sphs = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Pharmacy and Health Sciences' ;
        });

    $sphsM = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Pharmacy and Health Sciences' && $vote['gender'] == 'M' ;
        });

    $sphsF = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Pharmacy and Health Sciences' && $vote['gender'] == 'F' ;
        });

    $shss = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Humanities and Social Sciences' ;
        });

    $shssM = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Humanities and Social Sciences' && $vote['gender'] == 'M' ;
        });

    $shssF = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Humanities and Social Sciences' && $vote['gender'] == 'F' ;
        });

    $sccca = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Communication, Cinematic & Creative Arts' ;
        });

    $scccaM = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Communication, Cinematic & Creative Arts' && $vote['gender'] == 'M' ;
        });

    $scccaF = array_filter($all, function($vote){
              return $vote["vote"] == "1" && $vote['school'] == 'School of Communication, Cinematic & Creative Arts' && $vote['gender'] == 'F' ;
        });

    $male = count($boys);
    $female = count($girls);
    $first = count($year_1);
    $second = count($year_2);
    $third = count($year_3);
    $fourth = count($year_4);

    function getResults($position){

      $db = new DbOperations();

      $result = $db->readAspirantsbyPosition($position);

      $rows = $result->num_rows;

      if ($rows == 0){
        //There are no records to show
        echo "There are no Results yet!";

      }else {
        //Further processing of Data
        echo '<table class="table table-striped" style="margin-top:30px;" id="results">';
        echo '<thead class="thead-dark">';
        echo '<tr>';
        echo '<th scope="col">Image</th>';
        echo '<th scope="col">Name</th>';
        echo '<th scope="col">Votes</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
          echo "<tr>";

          echo "<td>";
          echo "<img src='uploads/".$row['picture']."' height='150px' width='150px' class='rounded-circle'>";
          echo "</td>";

          echo "<td>";
          echo $row['firstname']." ".$row['lastname'];
          echo "</td>";

          echo "<td>";
          echo $row['votes'];
          echo "</td>";

          echo "</tr>";
        }

        echo '</tbody>';
        echo '</table>';
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style type="text/css">


    html {
      background-color: #F9B552;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
        }
    body {

      background: none;

    }

    .showSingle {
      margin-right: 4px;
      margin-top: 0px;
      margin-bottom: 0px;
    }
    .add1{
      margin-right: 8px;
    }

    .viewChart {
      margin-top: 15px;
    }

    .summary {
      margin-top: 15px;
      float: right;
      text-align: justify;
    }
    .list-group{
    max-height: 332px;
    margin-bottom: 10px;
    overflow:hidden;
    -webkit-overflow-scrolling: touch;
}

    .list-group:hover {
      overflow-y: scroll;
    }

    </style>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>


    <title>Voting System</title>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="ecreploggedin.php">Overview <span class="sr-only">(current)</span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="viewforrep.php">View Users and Aspirants</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0" method="post">
      <button type="submit" name="logout" class="btn btn-light" style="background-color: #e3f2fd;">Logout</button>
    </form>

    </div>
    </nav>

    <br>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

<div class="collapse navbar-collapse" id="navbarSupportedContent1">

  <ul class="navbar-nav mr-auto">


<button class="btn btn-outline-success showSingle" type="button"  target="1">

  <div class="" style="width: 180px">
    <p><img src="images/graph.png" alt="" style="width: 100px; height: 100px; float: left;"></p>

    School Vs <br>Students
  </div>

</button>

<button class="btn btn-outline-success showSingle add1" type="button"  target="2">

  <div class="" style="width: 180px">
    <p><img src="images/bar.png" alt="" style="width: 100px; height: 100px; float: left;"></p>

    School Vs <br>Gender
  </div>

</button>

<button class="btn btn-outline-success showSingle" type="button"  target="3">

  <div class="" style="width: 180px">
    <p><img src="images/result.png" alt="" style="width: 100px; height: 100px; float: left;"></p>

    View Results
  </div>

</button>

<button class="btn btn-outline-success showSingle" type="button"  target="4">

  <div class="" style="width: 180px">
    <p><img src="images/bar1.png" alt="" style="width: 100px; height: 100px; float: left;"></p>

    Year Vs <br>Students
  </div>

</button>

<button class="btn btn-outline-success showSingle" type="button"  target="5">

  <div class="" style="width: 200px">
    <p><img src="images/chart.png" alt="" style="width: 100px; height: 100px; float: left;"></p>

    Students Voted <br> Vs <br>Students not <br> Voted
  </div>

</button>

<button class="btn btn-outline-success showSingle" type="button"  target="6">

  <div class="" style="width: 200px">
    <p><img src="images/donut.png" alt="" style="width: 100px; height: 100px; float: left;"></p>

    Male Vs <br>Female <br>Students Voted
  </div>

</button>
</ul>
</div>
</nav>

    <div class="container.col-xl-">

      <div id="error"><?php if ($error!="") {
        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

      } ?></div>

      <div id="success"><?php if ($success!="") {
        echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

      } ?></div>

      <div class="row">

      <div class="viewChart col">

        <div id="div1" class="targetDiv">

          <div id="barchart"></div>

        </div>

        <div id="div2" class="targetDiv">

          <div id="chart_div"></div>

        </div>

        <div id="div3" class="targetDiv">

          <div style="width:900px;">

            <form method="post">
              <div class="form-group">
                <label for="View Result">Position Title</label>
                <select class="form-control" name="position" id="position">
                  <option value="">Select...</option>
                  <?php
                  $db = new DbOperations();

                  $result = $db->readPositions();

                  while ($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['position']."'>" .$row['position']."</option>";
                  }
                    ?>
                </select>
              </div>

              <button type="submit" name="results" class="btn btn-primary btn-lg btn-block">View Results</button>
            </form>

          </div>

          <div style="width:900px;">

            <?php

              if (array_key_exists("results", $_POST)){
                $show= "1";
                if ($_POST['position'] != "") {
                  getResults($_POST['position']);
                }else {
                  echo "Please Select a position to continue!";
                }
              }

             ?>

          </div>

        </div>

        <div id="div4" class="targetDiv">

          <div id="barchart2"></div>

        </div>

        <div id="div5" class="targetDiv">

          <div id="donut3"></div>

        </div>

        <div id="div6" class="targetDiv">

          <div id="donut"></div>

        </div>

      </div>

      <div class="summary col-sm" align="right">

    <li class="list-group-item"> <h4>Election Summary</h4> </li>

        <ul class="list-group">
            <li class="list-group-item list-group-item-secondary">Accronyms:
            <ol >
              <li>SST: School of Science and Technology</li>
              <li>CSB: Chandaria School of Business</li>
              <li>SPHS: School of Pharmacy and Health Sciences</li>
              <li>SHSS: School of Humanities and Social Sciences</li>
              <li>SCCCA: School of Communication, Cinematic & Creative Arts</li>
            </ol>
            </li>
            <li class="list-group-item list-group-item-secondary">Total number of Students Voted: <?php echo count($voted); ?></li>
            <li class="list-group-item list-group-item-secondary">Number of Students not voted: <?php echo count($notVoted); ?></li>
            <li class="list-group-item list-group-item-secondary">Total number of Male Students Voted: <?php echo count($boys); ?></li>
            <li class="list-group-item list-group-item-secondary">Total number of Female Students Voted: <?php echo count($girls); ?></li>
            <li class="list-group-item list-group-item-secondary">Total number of Students Voted from SST: <?php echo count($sst); ?></li>
            <li class="list-group-item list-group-item-secondary">Total number of Students Voted from CSB: <?php echo count($csb); ?></li>
            <li class="list-group-item list-group-item-secondary">Total number of Students Voted from SHSS: <?php echo count($shss); ?></li>
            <li class="list-group-item list-group-item-secondary">Total number of Students Voted from SPHS: <?php echo count($sphs); ?></li>
            <li class="list-group-item list-group-item-secondary">Total number of Students Voted from SCCCA: <?php echo count($sccca); ?></li>
            <li class="list-group-item list-group-item-secondary">Total number of 1st Year Students who: <?php echo count($year_1); ?></li>
            <li class="list-group-item list-group-item-secondary">Total number of 2nd Year Students who: <?php echo count($year_2); ?></li>
            <li class="list-group-item list-group-item-secondary">Total number of 3rd Year Students who: <?php echo count($year_3); ?></li>
            <li class="list-group-item list-group-item-secondary">Total number of 4th Year Students who: <?php echo count($year_4); ?></li>
        </ul>

      </div>

      </div>



    </div>




    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Optional JavaScript -->
    <script type="text/javascript">

    jQuery(function(){
         jQuery('.targetDiv').hide();
         <?php
          if ($show === "1"){
            echo "jQuery('#div3').show();";
          }else {
            echo "jQuery('#div1').show();";
          }
          ?>
         //jQuery('#div1').show();
        jQuery('.showSingle').click(function(){
              jQuery('.targetDiv').hide();
              jQuery('#div'+$(this).attr('target')).show();
        });
});



      if ( window.history.replaceState ) {
          window.history.replaceState( null, null, window.location.href );
      }

    </script>

    <script type="text/javascript">

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(schoolVsStudents);
    google.charts.setOnLoadCallback(schoolsVsgender);
    google.charts.setOnLoadCallback(yearVsStudents);
    google.charts.setOnLoadCallback(votedVsNotVoted);
    google.charts.setOnLoadCallback(boysVsGirls);

    function schoolVsStudents() {
    var data = google.visualization.arrayToDataTable([
      ['School', 'No. Of Students', { role: 'style' } ],
      ['SST', <?php echo count($sst); ?>, 'color: gray'],
      ['CSB', <?php echo count($csb); ?>, 'color: #76A7FA'],
      ['SPHS', <?php echo count($sphs); ?>, 'opacity: 0.2'],
      ['SHSS', <?php echo count($shss); ?>, 'stroke-color: #703593; stroke-width: 4; fill-color: #C5A5CF'],
      ['SCCCA', <?php echo count($sccca); ?>, 'stroke-color: #871B47; stroke-opacity: 0.6; stroke-width: 8; fill-color: #BC5679; fill-opacity: 0.2']
    ]);

    var options = {
      title: 'Number of Students who voted from Different Schools',
      width: 900,
      height: 350,
      bar: {groupWidth: "55%"},
      legend: { position: "none" },
      backgroundColor: 'transparent',
      chartArea: {
        backgroundColor: 'transparent',
      },
    };

      var chart = new google.visualization.ColumnChart(document.getElementById('barchart'));

      chart.draw(data, options);
    }

    function schoolsVsgender() {
      // Some raw data (not necessarily accurate)
      var data = google.visualization.arrayToDataTable([
        ['School', 'Male', 'Female'],
        ['SST',  <?php echo count($sstM); ?>,      <?php echo count($sstF); ?>],
        ['CSB',  <?php echo count($csbM); ?>,      <?php echo count($csbF); ?>],
        ['SPHS',  <?php echo count($sphsM); ?>,      <?php echo count($sphsF); ?>],
        ['SHSS',  <?php echo count($shssM); ?>,      <?php echo count($shssF); ?>],
        ['SCCCA',  <?php echo count($scccaM); ?>,      <?php echo count($scccaF); ?>]
      ]);

      var options = {
        title : 'Number of Male and Female students who Voted in Different Schools',
        vAxis: {title: 'Number of Students'},
        hAxis: {title: 'Gender'},
        seriesType: 'bars',
        series: {5: {type: 'line'}},
        width: 900,
        height: 350,
        backgroundColor: 'transparent',
        chartArea: {
          backgroundColor: 'transparent',
        },
      };

      var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
      chart.draw(data, options);

    }

    function yearVsStudents() {
    var data = google.visualization.arrayToDataTable([
      ['Year', 'No. Of Students', { role: 'style' } ],
      ['Freshman', <?php echo count($year_1); ?>, 'color: gray'],
      ['Sophomores', <?php echo count($year_2); ?>, 'color: #76A7FA'],
      ['Juniors', <?php echo count($year_3); ?>, 'opacity: 0.2'],
      ['Seniors', <?php echo count($year_4); ?>, 'stroke-color: #703593; stroke-width: 4; fill-color: #C5A5CF']
    ]);

    var options = {
    title: 'Number of Students who voted from Different Years',
    width: 900,
    height: 350,
    orientation: 'vertical',
    legend: { position: 'top', maxLines: 3 },
    bar: { groupWidth: '75%' },
    backgroundColor: 'transparent',
    chartArea: {
      backgroundColor: 'transparent',
    },
    };

      var chart = new google.visualization.BarChart(document.getElementById('barchart2'));

      chart.draw(data, options);
    }

    function votedVsNotVoted() {

      var data = google.visualization.arrayToDataTable([
        ['Voted?', 'Number of Students'],
        ['Students who have Voted',     <?php echo count($voted); ?>],
        ['Students who have not Voted',      <?php echo count($notVoted); ?>]
      ]);

      var options = {
        title: 'No. of Students who Voted Vs No. of Students who havent voted',
        'chartArea': {'width': '600', 'height': '600'},
        width: 900,
        height: 400,
        is3D: true,
        backgroundColor: 'transparent',
        chartArea: {
          backgroundColor: 'transparent',
        },
      };

      var chart = new google.visualization.PieChart(document.getElementById('donut3'));

      chart.draw(data, options);
    }

    function boysVsGirls() {

      var data = google.visualization.arrayToDataTable([
        ['Gender', 'Number of Students'],
        ['Male',     <?php echo count($boys); ?>],
        ['Female',      <?php echo count($girls); ?>]
      ]);

      var options = {
        title: 'Boys Vs Girls who Voted',
        'chartArea': {'width': '600', 'height': '500'},
        width: 800,
        height: 600,
        backgroundColor: 'transparent',
        chartArea: {
          backgroundColor: 'transparent',
        },
        pieHole: 0.5,
      };

      var chart = new google.visualization.PieChart(document.getElementById('donut'));

      chart.draw(data, options);
    }

    </script>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
