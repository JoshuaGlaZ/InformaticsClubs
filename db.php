<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "esport";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Failed to connect to MySQL: " . $conn->connect_error);
}
?>