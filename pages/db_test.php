<?php

$servername = "mysql.hostinger.co.uk";
$database = "u365987764_data2";
$username = "u365987764_user2";
$password = "password";
 
// Create connection
 
$conn = mysqli_connect($servername, $username, $password, $database);
 
// Check connection
 
if (!$conn) {
 
    die("Connection failed: " . mysqli_connect_error());
 
}
echo "Connected successfully";
mysqli_close($conn);
?>