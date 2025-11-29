<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house_rental_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if(!$conn) {
    die("Connection failed");
}else{
    echo "Connected successfully";
}