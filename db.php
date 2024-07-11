<?php
    $servername = 'localhost';
    $username = 'root';
    $password = "";
    $database = "chat"; 
    $conn = mysqli_connect($servername,$username,$password,$database);
    if(!$conn){
        die('Cannot connect to the data base');
    }
?>
