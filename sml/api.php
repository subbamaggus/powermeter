<?php

require("config.php");
require("archiver.php");

$date_from = get_control($_REQUEST, "date_from", NULL);
$date_to = get_control($_REQUEST, "date_to", NULL);
$sensor = get_control($_REQUEST, "sensor", "energy");

$myAPI = new MyArchiverAPI();
$myAPI->set_db_connection($dbhost, $dbuser, $dbpass, $dbname);

if(NULL == $date_from)
  $result = $myAPI->getLatestRecord('data');
else
  $result = $myAPI->getData($sensor, $date_from, $date_to);

$myresult = json_encode($result);

echo $myresult;
?>