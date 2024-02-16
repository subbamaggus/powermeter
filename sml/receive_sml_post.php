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
    echo "error: not authenticated";
    die();
}

$sql = "SELECT 1_8_0 FROM power ORDER BY time DESC LIMIT 1";
$result = query($conn, $sql);
$row = $result->fetch_assoc();
$last_value = $row["1_8_0"];

$time = $myjsondata->StatusSNS->Time;
$time = str_replace("T", " ", $time);
$value = $myjsondata->StatusSNS->SML->{'1_8_0'};
$difference = $value - $last_value;

$sql = "INSERT INTO power (time, 1_8_0, diference) VALUES ('${time}', '${value}', '${difference}')";
$result = query($conn, $sql);

/*

prune concept: (separate script)
keep the last week.
delete all except one per hour for the last 4 weeks
delete all except one per day for the restore_error_handler

age
0-7     all
7-28    hourly
>28     daily

*/

$myjson = json_decode(file_get_contents("php://input"), true);
$myjsondata = json_decode($myjson);

echo $myjsondata->StatusSNS->Time . " - " . $myjsondata->StatusSNS->SML->{'1_8_0'} . " - " . $myjsondata->StatusSNS->SML->{'1_7_255'};
echo "updated: " . $time . " - " . $value . " - " . $difference;

?>