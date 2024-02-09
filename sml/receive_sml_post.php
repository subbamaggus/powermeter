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

echo "success really";


?>