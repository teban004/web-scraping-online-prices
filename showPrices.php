<?php
    require "./pricesConfig.php";

    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        echo "Config: ".$servername." ".$username." ".$password;
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";
?>