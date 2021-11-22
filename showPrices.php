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
            max-width: 1200px;
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
            text-align: center;
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

    $sql = "SELECT * FROM `products` ORDER BY `registered_time`;";
    $productsResult = $conn->query($sql);
    if( $productsResult->num_rows > 0) {
        
        while( $productData = $productsResult->fetch_assoc()) {
            
            $sql = "SELECT `price_history`.`text`, `price_history`.`registered_time`, `products`.`product_name` FROM `price_history` LEFT JOIN `products` ON `price_history`.`product_id` = `products`.`product_id` WHERE `price_history`.`product_id`=" . $productData['product_id'] . " ORDER BY `price_history`.`registered_time` DESC LIMIT 0, 1000;";
            $result = $conn->query($sql);

            echo "<p>Total records fetched for <b>".$productData['product_name']."</b>: ". $result->num_rows . "</p>";
            if( $result->num_rows > 0) {
                $count = 0;
                $priceText = null;
                $registeredTime = null;
                echo "<table id='prices'> <tr> <th>Count</th><th>Price text</th><th>Last update</th></tr>";
                while( $row = $result->fetch_assoc()) {
                    if( $row['text'] == $priceText ) {
                        $count++;
                    }
                    else {
                        if( $count>0 ) {
                            echo "<tr><td>". $count. "</td><td>". $priceText . "</td><td>". $registeredTime . "</td></tr>";
                        }
                        $count = 1;
                        $priceText = $row['text'];
                        $registeredTime = $row['registered_time'];
                    }
                }
                echo "<tr><td>". $count. "</td><td>". $priceText . "</td><td>". $registeredTime . "</td></tr>";
                echo "</table>";
            }
            else {
                echo "0 results";
            }
        }
    }
    else {
        echo "There are no registered products to show.";
    }
    
    $conn->close();
    //echo "<p>The connection to the database has been closed.</p>";
?>

    </body>
</html>