<?php

class DbOperations{

  private $con;

  function __construct(){

    require_once dirname(__FILE__).'/DbConnect.php';

    $db = new DbConnect();

    $this->con = $db->connect();

  }

  //Public functions

  //Database DbOperations for Voting system
  //The Login Functions
  /* Admin Login Function */

  public function adminLogin($email, $pass){
    $response = array();
    $stmt = $this->con->prepare("SELECT * FROM `admins` WHERE `Username` = ?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    if ($numRows > 0) {
      //Admin Exists
      while ($row = $result->fetch_assoc()){
        $db_password = $row['Password'];
        if (password_verify($pass, $db_password)) {
          //Verified
          $response['error'] = false;
          $response['id'] = $row['id'];
          $response['Classification'] = $row['Classification'];
        }else {
          //wrong password
          $response['error'] = true;
          $response['message'] = "Inavlid Credentials";
        }
      }
    }else {
      //admin does not exist
      $response['error'] = true;
      $response['message'] = "Inavlid Credentials";
    }

    return $response;
  }

  /* Student Login Function */

  public function userLogin($id){
    $response = array();
    $stmt = $this->con->prepare("SELECT * FROM `users` WHERE `id` = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    if ($numRows > 0) {
      //User Exists
      while ($row = $result->fetch_assoc()){
        $response= $row;
        $response['error'] = false;
      }
    }else {
      //User does not exist
      $response['error'] = true;
      $response['message'] = "Inavlid ID Number";
    }

    return $response;
  }

  /* THE READING FUNCTIONS ARE DECLARED BELOW */

  /* Reading the Schools */
  public function readSchools(){

    $stmt = $this->con->prepare("SELECT * FROM `schools`");
    $stmt->execute();
    $result = $stmt->get_result();
    //returns multidimenstional array aspirants
    return $result;
  }
  /* Reading the Positions */
  public function readPositions(){

    $stmt = $this->con->prepare("SELECT * FROM `positions`");
    $stmt->execute();
    $result = $stmt->get_result();
    //returns multidimenstional array Position as Object
    return $result;
  }
  /* Reading the Aspirants */
  public function readAspirants(){

    $stmt = $this->con->prepare("SELECT * FROM `aspirants`");
    $stmt->execute();
    $result = $stmt->get_result();
    //returns multidimenstional array aspirants as Object
    return $result;
  }
  /* Reading the Aspirants pictures to be deleted */
  public function deletePicture($position){
    $stmt = $this->con->prepare("SELECT * FROM `aspirants` WHERE `position` = ?");
    $stmt->bind_param("s",$position);
    $stmt->execute();
    $result = $stmt->get_result();
    //returns multidimenstional array aspirants
    return $result;
  }
  /* Reading the Aspirants pictures to be deleted by ID */
  public function deletePicturebyID($id){
    $stmt = $this->con->prepare("SELECT * FROM `aspirants` WHERE `id` = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    //returns multidimenstional array aspirants
    return $result;
  }
  /* Reading the Aspirants by Position */
  public function readAspirantsbyPosition($position){

    $stmt = $this->con->prepare("SELECT * FROM `aspirants` WHERE `position` = ? ORDER BY `votes` DESC");
    $stmt->bind_param("s",$position);
    $stmt->execute();
    $result = $stmt->get_result();
    //returns multidimenstional array aspirants as Object
    return $result;
  }
  /* Reading the Users */
  public function readUsers(){

    $stmt = $this->con->prepare("SELECT * FROM `users`");
    $stmt->execute();
    $result = $stmt->get_result();
    //returns multidimenstional array of users as Object
    return $result;
  }

  /* THE VOTING SESSIONS FUNCTIONS ARE DECLARED BELOW */

  /* Check to see if Session is Open or not */
  public function isSessionOpen(){

    $stmt = $this->con->prepare("SELECT session FROM `session` LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    if ($numRows>0) {
      $row = $result->fetch_assoc();
      if ($row['session'] == 1) {
        //session is open
        return 0;
      }else {
        //session is not open
        return 1;
      }
    }else {
      //Query error
      return 2;
    }
  }
  /* Start the Voting Session */
  public function startSession(){

    $stmt = $this->con->prepare("SELECT session FROM `session` LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    if($numRows > 0){
      $row = $result->fetch_assoc();
      $sql = $this->con->prepare("UPDATE `session` SET `session`='1' WHERE `session` =?");
      $sql->bind_param("i",$row['session']);
      if($sql->execute()){
        //session started
        return 0;
      }else {
        //something went wrong
        return 1;
      }
    }else {
      //Query error
      return 2;
    }
  }
  /* Stop the Voting Session */
  public function stopSession(){

    $stmt = $this->con->prepare("SELECT session FROM `session` LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    if($numRows > 0){
      $row = $result->fetch_assoc();
      $sql = $this->con->prepare("UPDATE `session` SET `session`='0' WHERE `session` =?");
      $sql->bind_param("i",$row['session']);
      if($sql->execute()){
        //session Stopped
        return 0;
      }else {
        //something went wrong
        return 1;
      }
    }else {
      //Query Error
      return 2;
    }
  }
  /* THE VOTING FUNCTION IS DECLARED BELOW */
  public function userVote($aspirantID, $userID){
    if ($this->isUserExist($userID) and $this->isAspirantExist($aspirantID)){
      //user exists
      //fetch the votes
      $error = 0;
      $stmt = $this->con->prepare("SELECT `votes` FROM `aspirants` WHERE `id`=?");
      $stmt->bind_param("s",$aspirantID);
      if($stmt->execute()){
        $result = $stmt->get_result()->fetch_assoc();
        $vote = $result['votes'] + 1;
        //update votes
        $sql = $this->con->prepare("UPDATE `aspirants` SET `votes`= ? WHERE `id` = ?");
        $sql->bind_param("ii", $vote, $aspirantID);
        if($sql->execute()){
          //update user has Voted
          $sql = $this->con->prepare("UPDATE `users` SET `vote`= 1 WHERE `id` = ?");
          $sql->bind_param("i", $userID);
          if($sql->execute()){
            return 0;
          }else {
            return 1;
          }

        }else {
          return 1;
        }


      }else {
        return 1;
      }
    }else {
      //User or Aspirant does not exist
      return 2;
    }
  }

  /* THE INSERTING INTO DB FUNCTIONS ARE DECLARED BELOW */
  /* Adding Aspirants into the DB */
  public function addAspirant($id, $fname, $lname, $position, $agenda, $picture){

    if ($this->isAspirantExist($id)) {
      //aspirant already exists
      return 0;
    }else {
      $stmt = $this->con->prepare("INSERT INTO `aspirants` (`id`, `firstname`, `lastname`, `position`, `agenda`, `picture`, `votes`) VALUES (?,?,?,?,?,?,'0');");
      $stmt->bind_param("isssss", $id, $fname, $lname, $position, $agenda, $picture);
      if($stmt->execute()){
          //Aspirant added successfully
          return 1;
      }else{
        //try again later
          return 2;
      }
    }
  }
  /* Adding Users into the DB */
  public function addUsers($id, $firstname, $lastname, $email){

    if ($this->isUserExist($id)) {
      //user exists
      return 0;
    }else {
    $stmt = $this->con->prepare("INSERT into `users` (`id`, `firstname`, `lastname`, `email`, `vote`) VALUES (?, ?, ?, ?, '0');");
    $stmt->bind_param("isss", $id, $firstname, $lastname, $email);
    if($stmt->execute()){
        //successfull
        return 1;
    }else{
      //try again later
        return 2;
    }
  }
  }
  //
  
  public function readSpecificUsers($id){

      if ($this->isUserExist($id)) {
        //user exists
        $stmt = $this->con->prepare("SELECT * FROM `users` WHERE `id` = ?");
        $stmt->bind_param("i",$id);
        if($stmt->execute()){

          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          //returns associative array of the first row
          return $row;

        }else{
            return 0;
        }

      }else {
        return 1;
      }
    }
  
  public function userVerification($id, $verification, $input){

      if ($this->isUserExist($id)) {
        //user exists

        if ($verification == $input) {
          // user verified
          $stmt = $this->con->prepare("UPDATE `users` SET `verify`= '1' WHERE `id` = ?");
          $stmt->bind_param("i", $id);

          if($stmt->execute()){
            //successfull
              return 0;
          }else{
            //failed
              return 1;
          }
        }else {
          // verification Wrong
          return 2;
        }

      }else {
        //user does not exist
        return 3;
      }

    }
  
  /* Adding or Creating Positions into the DB */
  public function addPosition($name){

    if ($this->isPositionExist($name)) {
      //position already exists
      return 0;
    }else {
      $stmt = $this->con->prepare("INSERT INTO `positions` (`position`) VALUES (?);");
      $stmt->bind_param("s", $name);
      if($stmt->execute()){
          //position added successfully
          return 1;
      }else{
        //try again later
          return 2;
      }
    }
  }
  /* Adding or Creating Electoral Commission EC into the DB */
  public function addRep($id, $fname, $lname, $email, $pass, $role){

    if ($this->isAdminExist($id)){
      //admin exists
      return 0;
    }else {
    $password = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $this->con->prepare("INSERT INTO `admins` (`id`, `First Name`, `Last Name`, `Username`, `Password`, `Classification`) VALUES (?, ?, ?, ?, ?, ?);");
    $stmt->bind_param("isssss", $id, $fname, $lname, $email, $password, $role);
    if($stmt->execute()){
        //successfull
        return 1;
    }else{
      //try again later
        return 2;
    }
  }
  }
  /* Adding User Information into the DB */
  public function updateUserDetails($school, $year, $gender, $id){

    if ($this->isUserExist($id)) {
      //user exists
      $stmt = $this->con->prepare("UPDATE `users` SET `school`= ?, `year`= ?, `gender`= ? WHERE `id` = ?");
      $stmt->bind_param("siss",$school, $year, $gender, $id);
      if($stmt->execute()){
          if ($this->userUpdatedDetails($id)){
            //user updated info successfully
            return 0;
          }else {
            //failed entry but successfull update
            return 1;
          }
      }else{
        //failed entry
          return 2;
      }
    }else {
      //user does not exist
      return 3;
    }
  }

  /* DELETING FROM DATABASE FUNCTIONS DECLARED HERE */

  /* Deleting the Aspirant */
  public function deleteAspirant($id){

    if ($this->isAspirantExist($id)){
      //Aspirant by that ID Exists
      $stmt = $this->con->prepare("DELETE FROM `aspirants` WHERE `id` = ?");
      $stmt->bind_param("i",$id);
      if($stmt->execute()){
        //aspirant Deleted
          return 0;
      }else{
        //Try again later
          return 1;
      }
    }else {
      // Dublicate or No aspirant by this ID
      return 2;
    }
  }
  /* Deleting the Positions */
  public function deletePosition($name){

    if ($this->isPositionExist($name)){
      //Position exists
      $stmt = $this->con->prepare("DELETE FROM `positions` WHERE `position` = ?");
      $stmt->bind_param("s",$name);
      if($stmt->execute()){
        //Position Deleted
          $sql = $this->con->prepare("DELETE FROM `aspirants` WHERE `position` = ?");
          $sql->bind_param("s",$name);
          if($sql->execute()){
            //Related Aspirnts Deleted
            return 0;
          }else {
            //Failed to delete the Aspirants
            return 1;
          }
      }else{
        //Try again later
          return 2;
      }
    }else {
      //Two or No Position by that Name
      return 3;
    }
  }

  /* PRIVATE FUNCTIONS TO BE CALLED FROM WITHIN */
  private function isUserExist($id){
    $stmt = $this->con->prepare("SELECT * FROM `users` WHERE `id`=?");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
  }
//check if an admin exists by the ID number in the admin database
  private function isAdminExist($id){
    $stmt = $this->con->prepare("SELECT * FROM `admins` WHERE `id`=?");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
  }
//check if position already exists
  private function isPositionExist($name){
    $stmt = $this->con->prepare("SELECT * FROM `positions` WHERE `position`=?");
    $stmt->bind_param("s",$name);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
  }
//check if aspirant already exists
  private function isAspirantExist($id){
    $stmt = $this->con->prepare("SELECT * FROM `aspirants` WHERE `id`=?");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
  }
  private function userUpdatedDetails($id){
    $stmt = $this->con->prepare("UPDATE `users` SET `updated`= '1' WHERE `id` = ?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
      //successfull
        return true;
    }else{
      //failed
        return false;
    }
  }
}


 ?>
