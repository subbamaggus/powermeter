<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house";

if ("localhost" != $_SERVER["SERVER_NAME"])
    require('config.php');

function connect($servername, $username, $password, $dbname) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("latin1_german1_ci");
    
    return $conn;
}

$conn = connect($servername, $username, $password, $dbname) or die("Connection failed: " . connect_error());
if (connect_errno()) {
    printf("Connect failed: %s\n", connect_error());
    exit();
}

function connect_error() {

    return mysqli_connect_error();
}

function connect_errno() {

    return mysqli_connect_errno();
}

function query($conn, $sql) {

    return $conn->query($sql);
}

function error($conn) {

    return mysqli_error($conn);
}

function fetch_assoc($res) {

    return mysqli_fetch_assoc($res);
}


?>