<?PHP

require("sql.php");

function get_control($myget, $key, $default) {
    $mydefault = $default;

    if(isset($myget[$key]))
        $mydefault = $myget[$key];

    return $mydefault;
}

$token = get_control($_REQUEST, "token", NULL);

if('XXXXXXXX' <> $token) {
    echo "error";
    die();
}
    
//$sql = "INSERT INTO data_object (title, name, address, contact, fk_object) VALUES ('${title}', '${name}', '${address}', '${contact}', '${parent}')";
//$result = query($conn, $sql);

/*

only log every 10 seconds

prune concept:
keep the last week.
delete all except one per hour for the last 4 weeks
delete all except one per day for the restore_error_handler

*/

$myjson = json_decode(file_get_contents("php://input"), true);
$myjsondata = json_decode($myjson);

echo $myjsondata->StatusSNS->Time . " - " . $myjsondata->StatusSNS->SML->{'1_8_0'} . " - " . $myjsondata->StatusSNS->SML->{'1_7_255'};


?>