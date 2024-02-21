<?php

function get_control($myget, $key, $default) {
  $mydefault = $default;

  if(isset($myget[$key]))
    $mydefault = $myget[$key];

  return $mydefault;
}

class MyArchiverAPI {
  protected static $mysqli;

  function set_db_connection ($dbhost, $dbuser, $dbpass, $dbname) {

    self::$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if (self::$mysqli->connect_errno) {
      die("could not connect: " . self::$mysqli->connect_error);
    }
  }

  function storeData($myjsondata) {

    $sql = "SELECT * FROM power ORDER BY time DESC LIMIT 1";
    $statement = self::$mysqli->prepare($sql);
    $statement->execute();
    
    $result = $statement->get_result();
    
    $all_items = mysqli_fetch_all($result,MYSQLI_ASSOC);
    
    $last_value = $all_items[0]["1_8_0"];
    $last_time = $all_items[0]["time"];
    
    // format and calulate data
    $time = $myjsondata->StatusSNS->Time;
    $time = str_replace("T", " ", $time);
    $value = $myjsondata->StatusSNS->SML->{'1_8_0'};
    $diff_energy = $value - $last_value;
    $diff_time = strtotime($time) - strtotime($last_time);
    $energy = ($diff_energy * 3600 * 1000) / $diff_time;
    $oil1 = $myjsondata->oil->Wert1;
    if(NULL == $oil1)
        $oil1 = -1;
    $oil2 = $myjsondata->oil->Wert2;
    if(NULL == $oil2)
        $oil2 = -1;
    
    $sql = "INSERT INTO power (time, 1_8_0, diff_energy, diff_time, energy, oil1, oil2) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $statement = self::$mysqli->prepare($sql);
    $statement->bind_param('sddiddd', $time, $value, $diff_energy, $diff_time, $energy, $oil1, $oil2);
    $statement->execute();
    
    $error = $statement->errno;
    if("" <> $error) {
      echo $sql . "\n";
      echo $statement->error . "\n";
    }
    
    $return_result = $myjsondata->StatusSNS->Time . " - " . $myjsondata->StatusSNS->SML->{'1_8_0'} . " - " . $myjsondata->StatusSNS->SML->{'1_7_255'} . "\r\n";
    $return_result .= "updated: " . $time . " - " . $value . " - " . $diff_energy . " - " . $diff_time . " - " . $energy . "\r\n";
    
    return $return_result;
  }
  
  function getLatestRecord($sensor) {
    $sql = "SELECT * FROM power ORDER BY time DESC LIMIT 1";
           
    $statement = self::$mysqli->prepare($sql);
    $statement->execute();
    
    $result = $statement->get_result();

    $all_items = mysqli_fetch_all($result,MYSQLI_ASSOC);
    
    $date = $all_items[0]['time'];
    
    $value = $all_items[0]['energy'];
    
    $return_result = [ "date" => $date,
            "energy" => $value
          ];
          
    return $return_result;
  }

  function getData($sensor, $from, $to) {
    $sql = "SELECT * FROM power WHERE time > ? and time < ? ORDER BY time";
           
    $statement = self::$mysqli->prepare($sql);
    $statement->bind_param('ss', $from, $to);
    $statement->execute();
    
    $result = $statement->get_result();

    $all_items = mysqli_fetch_all($result,MYSQLI_ASSOC);
    
    $return_result = array();
    foreach ($all_items as $item) {
      $return_result[] = [ "date" => $item['time'],
            "value" => $item[$sensor]
          ];
    }
          
    return $return_result;
  }  
}

?>