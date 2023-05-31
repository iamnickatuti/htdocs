<?php
$servername = "52.173.217.76:3306";
$username = "bireports";
$password = "uZAUBUotYV1Ai8uS#";
$dbname = "cradle";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

