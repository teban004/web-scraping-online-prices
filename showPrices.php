<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Online prices</title>
        <style>
            #prices {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            max-width: 600px;
            }
            
            #prices td, #prices th {
            border: 1px solid #ddd;
            padding: 8px;
            }
            
            #prices tr:nth-child(even){background-color: #f2f2f2;}
            
            #prices tr:hover {background-color: #ddd;}
            
            #prices th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #5b7a6f;
            color: white;
            }
        </style>
    </head>
    <body>

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

    echo "<p>Total records fetched: ". $result->num_rows . "</p>";
    if( $result->num_rows > 0) {
        $prices = array();
        $update_time = null;
        while( $row = $result->fetch_assoc()) {
            if( array_key_exists($row['text'], $prices)) // check if the text exists in the prices array
                $prices[$row['text']] = $prices[$row['text']] + 1;
            else // if it doesn't exist, creates it initialized to 1
                $prices[$row['text']] = 1;

            if( empty($update_time) )
                $update_time = $row['registered_time'];
        }
        
        echo "<p>Last update: " . $update_time . "</p>";
        echo "<table id='prices'> <tr> <th>Count</th><th>Price text</th></tr>";
        foreach( $prices as $priceText => $count) {
            echo "<tr><td>". $count. "</td><td>". $priceText . "</td></tr>";
        }
        echo "</table>";
    }
    else {
        echo "0 results";
    }
    
    $conn->close();
    //echo "<p>The connection to the database has been closed.</p>";
?>

    </body>
</html>