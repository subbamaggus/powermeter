<?PHP

require("config.php");
require("archiver.php");

$token = get_control($_REQUEST, "token", NULL);

if($secret_token <> $token) {
  echo "error: not authenticated";
  die();
}

$myjson = json_decode(file_get_contents("php://input"), true);
$myjsondata = json_decode($myjson);

$myAPI = new MyArchiverAPI();
$myAPI->set_db_connection($dbhost, $dbuser, $dbpass, $dbname);

$result = $myAPI->storeData($myjsondata);

//$myresult = json_encode($result);

echo $result;

?>