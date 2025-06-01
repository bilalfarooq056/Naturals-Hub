<?php
$servername = "localhost";
$username = "root"; // default in XAMPP
$password = "";     // default is empty
$database = "dbms_project"; // (your created database name)

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
