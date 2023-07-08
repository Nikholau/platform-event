<?php

function getConnection() {
    $servername = 'db';
    $username = 'root';
    $password = 'admin';
    $database = 'labprog';
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_errno) {
        die('Connection failed: ' . $conn->connect_errno);
    }
    return $conn;
}
?>
