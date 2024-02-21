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
    $return_result = "";
    if("" <> $error) {
      $return_result .=  $sql . "\n";
      $return_result .=  $statement->error . "\n";
    }
    
    $return_result .= $myjsondata->StatusSNS->Time . " - " . $myjsondata->StatusSNS->SML->{'1_8_0'} . " - " . $myjsondata->StatusSNS->SML->{'1_7_255'} . "\r\n";
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
  
  function purgeDB($limit1, $limit2) {
/*

purge concept: (separate script)
keep the last week.
delete all except one per hour for the last 4 weeks
delete all except one per day for the restore_error_handler

age
0-7     all
7-28    hourly
>28     daily

-- delete sets:
-- DELETE
SELECT *
FROM power
WHERE time < DATE_SUB(NOW(), INTERVAL 2 DAY)
AND time > DATE_SUB(NOW(), INTERVAL 4 DAY)
AND id NOT IN (SELECT min(id)
    FROM `power` 
    WHERE time < DATE_SUB(NOW(), INTERVAL 2 DAY)
    AND time > DATE_SUB(NOW(), INTERVAL 4 DAY)
    GROUP by DATE_FORMAT(time, '%d-%m-%Y %H'))


-- delete sets:
-- DELETE 
SELECT *
FROM power 
WHERE time < DATE_SUB(NOW(), INTERVAL 4 DAY)
AND id NOT IN (SELECT min(id)
    FROM `power` 
    WHERE time < DATE_SUB(NOW(), INTERVAL 4 DAY)
    GROUP by DATE_FORMAT(time, '%d-%m-%Y'))

    
*/
    // recalc all values between 2 and 5 days
    $sql = "SELECT * FROM power WHERE time < DATE_SUB(NOW(), INTERVAL " . $limit1 . " DAY) AND time > DATE_SUB(NOW(), INTERVAL " . ($limit2 + 1) . " DAY) ORDER BY time";
           
    $statement = self::$mysqli->prepare($sql);
    $statement->execute();
    
    $result = $statement->get_result();

    $all_items = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $count = count($all_items);

    $return_value = "";    
    for($i = 1; $i < $count; $i++) {

      $diff_energy = $all_items[$i]['1_8_0'] - $all_items[$i - 1]['1_8_0'];
      $diff_time = strtotime($all_items[$i]['time']) - strtotime($all_items[$i - 1]['time']);
      $energy = ($diff_energy * 3600 * 1000) / $diff_time;
      
      if($diff_time <> $all_items[$i]['diff_time']) {
        $return_value .= $all_items[$i]['id'] . " - " . $all_items[$i]['time'] . " - " . $all_items[$i]['1_8_0'] . " - " . $all_items[$i]['diff_time'] . "\r\n";
        
        $update_sql = "UPDATE power SET diff_energy = ?, diff_time = ?, energy = ? where id = ?";
        $return_value .= $update_sql . "\r\n";
        $return_value .= "$diff_energy - $diff_time - $energy - " . $all_items[$i]['id'] . "\r\n";
        $update_statement = self::$mysqli->prepare($update_sql);
        $update_statement->bind_param('didi', $diff_energy, $diff_time, $energy, $all_items[$i]['id']);
        $update_statement->execute();
    
        $update_result = $update_statement->get_result();
        $error = $update_statement->errno;

        if("" <> $error) {
          $return_value .=  $update_sql . "\n";
          $return_value .=  $update_statement->error . "\n";
        }
        
      }
    }
          
    return $return_value;      
  }
}

?>