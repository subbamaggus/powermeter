<?php

require("config.php");
require("archiver.php");

$myAPI = new MyArchiverAPI();
$myAPI->set_db_connection($dbhost, $dbuser, $dbpass, $dbname);

$result = $myAPI->getLatestRecord('data');

$myresult = json_encode($result);

echo $myresult;
?>