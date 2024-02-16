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

$myjson = json_decode(file_get_contents("php://input"), true);
$myjsondata = json_decode($myjson);

$sql = "SELECT * FROM power ORDER BY time DESC LIMIT 1";
$result = query($conn, $sql);
$row = $result->fetch_assoc();
$last_value = $row["1_8_0"];
$last_time = $row["time"];

$time = $myjsondata->StatusSNS->Time;
$time = str_replace("T", " ", $time);
$value = $myjsondata->StatusSNS->SML->{'1_8_0'};
$diff_energy = $value - $last_value;
$diff_time = strtotime($time) - strtotime($last_time);
$energy = ($diff_energy * 3600 * 1000) / $diff_time;

$sql = "INSERT INTO power (time, 1_8_0, diff_energy, diff_time, energy) VALUES ('${time}', '${value}', '${diff_energy}', '${diff_time}', '${energy}')";
$result = query($conn, $sql);

$error = error($conn);
if("" <> $error) {
    echo $sql;
    echo $error;
}

echo $myjsondata->StatusSNS->Time . " - " . $myjsondata->StatusSNS->SML->{'1_8_0'} . " - " . $myjsondata->StatusSNS->SML->{'1_7_255'} . "\r\n";
echo "updated: " . $time . " - " . $value . " - " . $diff_energy . " - " . $diff_time . " - " . $energy . "\r\n";

?>