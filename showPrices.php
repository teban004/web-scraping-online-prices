<?php
    error_reporting(E_ALL);

    require "./pricesConfig.php";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $db);

    // Check connection
    if ($conn->connect_error) {
        echo "Config: ".$servername." ".$username." ".$password;
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM `price_history` WHERE `product_id`=1 ORDER BY `registered_time` DESC LIMIT 0, 1000;";
    $result = $conn->query($sql);

    echo "Total records fetched: ". $result->num_rows;
    if( $result->num_rows > 0) {
        $prices = array();
        while( $row = $result->fetch_assoc()) {
            if( in_array($row['text'], $prices)) // check if the text exists in the prices array
                $prices[$row['text']] = $prices[$row['text']] + 1;
            else // if it doesn't exist yet, adds it
                $prices[$row['text']] = 1;
        }
        foreach( $prices as $priceText => $count) {
            print("( ". $count. " ):  ". $priceText);
        }
    }
    else {
        echo "0 results";
    }
    
    $conn->close();
    echo "\nThe connection to the database has been closed.";
?>