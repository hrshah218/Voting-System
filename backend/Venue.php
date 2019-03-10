<?php
  include_once("DbManager.php");


  class Vote {
  	private $user = array();
  	private $db_handle = null;


    public function __construct(){
       $dbm = new DbManager();
       $this->db_handle = $dbm->getHandle();
    }

    public function __toString(){
       return "name ". $this->getName() ." , id ". $this->getID();
    }

    // @mutators
    public function setUser($date){
        $this->user = $date;
    	return $this;
    }

    // @accessors
    public function getUser(){
    	return $this->user;
    }







    // get a venue objects properties in an array
    public function setProperties(){
      $venue = new Venue();
      $combined_properties = array(
              'venue'=> NULL, 'events'=> array(), 'amenities'=> array(),
              'photos'=> array(), 'host'=> NULL
            );

      try{

        //get primary venue details like name etc
        $primary_query = "select * from venues where id = ?";
        $primary_stmt = $this->db_handle->prepare($primary_query);
        $primary_stmt->execute(array($this->getID()));
        $primary_results = $primary_stmt->fetch(PDO::FETCH_ASSOC);


        if(is_array($primary_results)){

          //get the events
          $events_query = "select * from hm_events where venue = ?";
          $events_stmt = $this->db_handle->prepare($events_query);
          $events_stmt->execute(array( $primary_results['name'] ));
          $events = $events_stmt->fetchAll(PDO::FETCH_ASSOC);

          //get event amenities
          $amenities_query = "select * from amenities where venue_id = ?";
          $amenities_stmt = $this->db_handle->prepare($amenities_query);
          $amenities_stmt->execute(array( $this->getID() ));
          $amenities = $amenities_stmt->fetchAll(PDO::FETCH_ASSOC);

          // get photos
          $photos_query = "select * from photos where venue_id = ?";
          $photos_stmt = $this->db_handle->prepare($photos_query);
          $photos_stmt->execute(array( $this->getID() ));
          $photos = $photos_stmt->fetchAll(PDO::FETCH_ASSOC);

          //get host
          $host_query = "select * from hm_hosts where id = ?";
          $host_stmt = $this->db_handle->prepare($host_query);
          $host_stmt->execute(array( $primary_results['host_id'] ));
          $host = $host_stmt->fetchAll(PDO::FETCH_ASSOC);


          // combine all these database properties in one array
          $combined_properties['venue'] = $primary_results;
          $combined_properties['host'] = $host;
          $combined_properties['amenities'] = $amenities;
          $combined_properties['photos'] = $photos;
          $combined_properties['events'] = $events;

          //pass the array to make functon which will create a new venue object
          $venue = Venue::make($combined_properties);
        }

      }catch(PDOException $e){
        echo($e->getMessage());
      }

      return $venue;
    }// public function setProperties(){ .. }

    // make the details of a host
    // database data is passed in an array then this method returns a host instance
    public static function make($venue_properties){
      $this_venue = new Venue();

      // unpack the properties from the venue_properties array
      $venue = $venue_properties['venue'];
      $host = $venue_properties['host'];
      $photos = $venue_properties['photos'];
      $amenities = $venue_properties['amenities'];
      $events = $venue_properties['events'];

      $this_venue->setID($venue['id'])
                  ->setName($venue['name'])
                  ->setSize($venue['size'])
                  ->setCapacity($venue['capacity'])
                  ->setPic( (new Pathos())->getPaths()['venues'] . $venue['pic'] )
                  ->setDescription($venue['description'])
                  ->setContacts(array(
                      'phone'=> $venue['phone'], 'email'=> $venue['email']
                    ))
                  ->setSocialHandles(array(
                      "fb"=> trim($venue['fb']),
                      "twitter"=> trim($venue['twitter']),
                      "ig"=> trim($venue['ig']
                    )))
                  ->setLocation(array(
                      'country'=> (new Locations())
                                      ->getCitiesCountry($venue['city']),
                      'city'=> $venue['city'],
                      'lat'=> $venue['lat'], 'lng'=> $venue['lng']
                    ));

      // set host data
      $host_data = Host::make($host)->getProperties();
      $this_venue->setHost(array( 'id'=> $host_data ));

      // this venue events
      $temp_events = array();
      if( is_array($events) && count($events) > 0 ){
        foreach($events as  $event){
          $event_details = Event::make(array(
                   'event'=> $event, 'host'=> array(),
                   'performers'=> array(), 'comments'=> array(),
                   'ticket_places'=> array(), 'hosts'=> array()
                ))->getProperties();

          array_push($temp_events, $event_details);
        }
      }
      $this_venue->setEvents($temp_events);


      // this venue amenities
      $temp_amenities = array();
      if($amenities != NULL){
        foreach($amenities as  $amenity){
          $amenity_details = Amenity::make($amenity)->getProperties();

          array_push($temp_amenities, $amenity_details);
        }
      }
      $this_venue->setAmenities($temp_amenities);

      // this venue photos
      $temp_photos = array();
      if($photos != NULL){
        foreach($photos as  $photo){
          $photo_details = Photo::make($photo)->getProperties();

          array_push($temp_photos, $photo_details);
        }
      }
      $this_venue->setPhotos($temp_photos);

      return $this_venue;
    }

    // create a venue instance given its id
    // it gets details from the database
    public function getProperties(){
      $properties = array();

      $properties['id'] = $this->getID();
      $properties['name'] = $this->getName();
      $properties['size'] = $this->getSize();
      $properties['capacity'] = $this->getCapacity();
      $properties['min_charge'] = $this->getCharges()['min'];
      $properties['pic'] = $this->getPic();
      $properties['social_handles'] = $this->getSocialHandles();
      $properties['contacts'] = $this->getContacts();

      $properties['photos'] = $this->getPhotos();
      $properties['location'] = $this->getLocation();
      $properties['description'] = $this->getDescription();

      $properties['host'] = $this->getHost();
      $properties['events'] = $this->getEvents();
      $properties['amenities'] = $this->getAmenities();

      return $properties;
    }// public function getProperties(){ .. }


    public function getVotes(){
      $all = array();
      $query = "select * from users";

      try{

        $smtmt = $this->db_handle->prepare($query);
        $smtmt->execute();

        $all = $smtmt->fetchAll(PDO::FETCH_ASSOC);

      }catch(PDOException $e){

      }

      return $all;

    }


        public function getAspirants(){
          $all = array();
          $query = "select * from aspirants";

          try{

            $smtmt = $this->db_handle->prepare($query);
            $smtmt->execute();

            $all = $smtmt->fetchAll(PDO::FETCH_ASSOC);

          }catch(PDOException $e){
            echo($e->getMessage());
          }

          return $all;

        }

        public function returnPositionArrays($array_data){
          $position_array = array();

          $positions = array_filter( $array_data, function($aspi){
                return $aspi['position'];
          } );

          foreach ($positions as $pos) {
            return array_push($positions, $pos, array());
          }

          return $positions;
        }

        // array(..)
        // array( 'chairman'=> array(), ''  )
        // $positions['chairman']


    // cleaning up
    public function __destruct(){
       $this->db_handle = null;
    }

  }// end of class


?>
