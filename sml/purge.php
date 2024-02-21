<?PHP

require("config.php");
require("archiver.php");

$token = get_control($_REQUEST, "token", NULL);

if($secret_token <> $token) {
  echo "error: not authenticated";
  die();
}

$myAPI = new MyArchiverAPI();
$myAPI->set_db_connection($dbhost, $dbuser, $dbpass, $dbname);

$result = $myAPI->purgeDB(2, 4);

echo $result;

?>