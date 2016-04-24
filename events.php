<?php

$GLOBALS['userLatitude'] = 0;
$GLOBALS['userLongitude'] = 0;
$GLOBALS['verifiedByNasa'] = "all";

function distance($lat1, $lon1, $lat2, $lon2) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  return ($miles * 1.609344);
}

function sortByX($a, $b) { 
  return true; //(distance($a->geometries[0]->coordinates[0][0], $a->geometries[0]->coordinates[0][1], $GLOBALS['userLatitude'], $GLOBALS['$userLongitude']) > distance($b->geometries[0]->coordinates[0][0], $b->geometries[0]->coordinates[0][1], $GLOBALS['$userLatitude'], $GLOBALS['$userLongitude'])); 
}

function getEvents($service_url, $latitude, $longitude) {

  $categoryColor['6'] = "";
  $categoryColor['7'] = "#7f8c8d";
  $categoryColor['16'] = "";
  $categoryColor['9'] = "#3498db";
  $categoryColor['14'] = "";
  $categoryColor['19'] = "";
  $categoryColor['15'] = "#83d6dd";
  $categoryColor['10'] = "#f1c40f";
  $categoryColor['17'] = "";
  $categoryColor['18'] = "";
  $categoryColor['12'] = "#d35400";
  $categoryColor['13'] = "#2980b9";
  $categoryColor['8'] = "#c0392b";

  $curl = curl_init($service_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $curl_response = curl_exec($curl);
  if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
  }
  curl_close($curl);

  $response = json_decode(utf8_encode($curl_response));

  $listEvents = $response->events;
  $listEventsVerified = [];

  $jsonEventString = file_get_contents("user_event.json");
  $jsonEvent = json_decode($jsonEventString, true);
  foreach ($jsonEvent as $event) {
    $event->verifiedByNasa = false;
    if ($GLOBALS['verifiedByNasa'] == 'si' && $event->verifiedByNasa == true) {
      $listEventsVerified[] = $event;
    } else if ($GLOBALS['verifiedByNasa'] == 'no' && $event->verifiedByNasa == false) {
      $listEventsVerified[] = $event;
    } else if ($GLOBALS['verifiedByNasa'] == 'all') {
      $listEventsVerified[] = $event;
    }
  }

  foreach ($listEvents as $event) {
    if (is_array($event->geometries[0]->coordinates[0][0][0])) {
      $event->geometries[0]->coordinates[0][0] = $event->geometries[0]->coordinates[0][0][0];
    }
    $event->imageUrl = "https://maps.googleapis.com/maps/api/staticmap?center=" . $event->geometries[0]->coordinates[0][0][1] . "," . $event->geometries[0]->coordinates[0][0][0] . "&zoom=5&size=512x256&key=AIzaSyDFMjXKAW4e8nlhWrtyMftJytS3G8pkwWU";
    $event->verifiedByNasa = true;
    $event->categories[0]->categoryColor = $categoryColor[$event->categories[0]->id];
    //foreach ($event->sources as $urlSource) {
    //  if ($urlSource->id == "EO") {
    //    $event->verifiedByNasa = true;
    //    break;
    //  }
    //}

    if ($GLOBALS['verifiedByNasa'] == 'si' && $event->verifiedByNasa == true) {
      $listEventsVerified[] = $event;
    } else if ($GLOBALS['verifiedByNasa'] == 'no' && $event->verifiedByNasa == false) {
      $listEventsVerified[] = $event;
    } else if ($GLOBALS['verifiedByNasa'] == 'all') {
      $listEventsVerified[] = $event;
    }

  }

  usort($listEvents, "sortByX");

  return $listEventsVerified;
  //echo(json_encode($listEvents, JSON_UNESCAPED_SLASHES));
}

$service_url = 'http://eonet.sci.gsfc.nasa.gov/api/v2.1/events?';

$GLOBALS['userLatitude'] = $_GET['latitude'];
$GLOBALS['userLongitude'] = $_GET['longitude'];
$GLOBALS['verifiedByNasa'] = $_GET['verifiedByNasa'] ? $_GET['verifiedByNasa'] : "all";

$postData = file_get_contents('php://input'); 

if ($postData) {

  $categoryObject['6']->id = "6";
  $categoryObject['6']->title = "Drought";
  $categoryObject['6']->categoryColor = "";
  $categoryObject['7']->id = "7";
  $categoryObject['7']->title = "Dust and Haze";
  $categoryObject['7']->categoryColor = "#7f8c8d";
  $categoryObject['16']->id = "16";
  $categoryObject['16']->title = "Earthquakes";
  $categoryObject['16']->categoryColor = "";
  $categoryObject['9']->id = "9";
  $categoryObject['9']->title = "Floods";
  $categoryObject['9']->categoryColor = "#3498db";
  $categoryObject['14']->id = "14";
  $categoryObject['14']->title = "Landslides";
  $categoryObject['14']->categoryColor = "";
  $categoryObject['19']->id = "19";
  $categoryObject['19']->title = "Manmade";
  $categoryObject['19']->categoryColor = "";
  $categoryObject['15']->id = "15";
  $categoryObject['15']->title = "Sea and Lake Ice";
  $categoryObject['15']->categoryColor = "#83d6dd";
  $categoryObject['10']->id = "10";
  $categoryObject['10']->title = "Severe Storms";
  $categoryObject['10']->categoryColor = "#f1c40f";
  $categoryObject['17']->id = "17";
  $categoryObject['17']->title = "Snow";
  $categoryObject['17']->categoryColor = "";
  $categoryObject['18']->id = "18";
  $categoryObject['18']->title = "Temperature Extremes";
  $categoryObject['18']->categoryColor = "";
  $categoryObject['12']->id = "12";
  $categoryObject['12']->title = "Volcanoes";
  $categoryObject['12']->categoryColor = "#d35400";
  $categoryObject['13']->id = "13";
  $categoryObject['13']->title = "Water Color";
  $categoryObject['13']->categoryColor = "#2980b9";
  $categoryObject['8']->id = "8";
  $categoryObject['8']->title = "Wildfires";
  $categoryObject['8']->categoryColor = "#c0392b";

  parse_str($postData, $postObject);

  var_dump($postObject);
  $eventToAdd->title = $postObject["title"];
  $eventToAdd->description = $postObject["description"];
  $eventToAdd->categories = [$categoryObject[$postObject["category"]]];

  $eventsToSave = [];

  $jsonEventString = file_get_contents("user_event.json");
  $jsonEvent = json_decode($jsonEventString, true);
  foreach ($jsonEvent as $event) {
    $eventsToSave[] = $event;
  }

  $eventsToSave[] = $eventToAdd;

  $fp = fopen('user_event.json', 'w');
  fwrite($fp, json_encode($eventsToSave, JSON_UNESCAPED_SLASHES));
  fclose($fp);

}else if ($_GET['status'] && $_GET['categories']) {
  $service_url = $service_url . "status=" . $_GET['status'];
  $events = getEvents($service_url, $latitude, $longitude);
  $categories = explode(',', $_GET['categories']);
  $filteredEvents = [];

  foreach ($events as $event) {
    foreach ($event->categories  as $category) {
      if (in_array($category->id, $categories)) {
        array_push($filteredEvents, $event);
        break;
      }
    }
  }
  echo(json_encode($filteredEvents, JSON_UNESCAPED_SLASHES));
} else if ($_GET['status']) {
  $service_url = $service_url . "status=" . $_GET['status'];
  $events = getEvents($service_url, $latitude, $longitude);
  echo(json_encode($events, JSON_UNESCAPED_SLASHES));
} else if ($_GET['categories']) {
  $service_url = 'http://eonet.sci.gsfc.nasa.gov/api/v2.1/categories/';
  $categories = explode(',', $_GET['categories']);

  $fusedEvent = [];

  foreach ($categories as $categoryId) {
    $events = getEvents($service_url . $categoryId, $latitude, $longitude);
    foreach ($events as $event) {
      $fusedEvent[] = $event;
    }
  }

  echo(json_encode($fusedEvent, JSON_UNESCAPED_SLASHES));
} else {
  $events = getEvents($service_url, $latitude, $longitude);
  echo(json_encode($events, JSON_UNESCAPED_SLASHES));
}
?>