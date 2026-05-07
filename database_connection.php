<?php
    
// to print the exact server side error instead of the error code like 501, 502
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//database info
$db_username = "if0_41780670";
$db_host     = "sql103.infinityfree.com";
$db_password = "pBTMWgZmV9pRI"; 
$db_name     = "if0_41780670_exploreksa";

$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if ($conn) {
    mysqli_set_charset($conn, "utf8mb4"); 
    echo "database connected seccussfully !!!! (:";
} 

else {
    $error = mysqli_connect_error();
    echo "connection failed: " . $error;
}
?>
