function shss(){
    $votes = array();
    $link = mysqli_connect("shareddb1e.hosting.stackcp.net", "voting-363716d0", "y6kqj1lgog", "voting-363716d0");

    if (mysqli_connect_error()) {

    die("Database Connection Error");

    }

    $query = "SELECT * FROM `users` where vote = '1'";

    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0){
      $shss = mysqli_num_rows($result);
      return $shss;
    }else {
      $shss = 0;
      return $shss;
    }

    // Function to get number of 1st year students who have voted

    $school_of_science = array_filter( function($user){
          return $user['vote'] == '1' && $user['school'] == "School Of Science";
    }, $allvotesdb );

    count($school_of_science)


}
