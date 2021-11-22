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
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    require "./pricesConfig.php";

    // Create connection
    $mysqli = new mysqli($servername, $username, $password, $db);

    // Check connection
    if ($mysqli->connect_error) {
        //echo "Config: ".$servername." ".$username." ".$password;
        die("Connection failed: " . $mysqli->connect_error);
    }

    // get the list of products
    $productsQuery = "SELECT * FROM `products` ORDER BY `registered_time`;";
    $productsResult = $mysqli->query($productsQuery);
    //echo "<p>" . $productsResult->num_rows . " results returned from the query.</p>";
    
    if( $productsResult->num_rows > 0) {
        // loop through each of the products and display the records in a table
        while( $productData = $productsResult->fetch_assoc()) {
            // create the query with the product_id as parameter
            $pricesQuery = "SELECT `price_history`.`text`, `price_history`.`registered_time`, `products`.`product_name` FROM `price_history` LEFT JOIN `products` ON `price_history`.`product_id` = `products`.`product_id` WHERE `price_history`.`product_id`=? ORDER BY `price_history`.`registered_time` DESC LIMIT 0, 1000;";
            $stmt = $mysqli->prepare($pricesQuery); // prepare the query in the mysql connection
            $stmt->bind_param("i", $productData['product_id']); // bind the parameter as an integer
            $stmt->execute(); // execute the prepared statement
            $result = $stmt->get_result(); // gets the result set from the prepared statement as a mysqli_result object

            echo "<p>Total records fetched for <b>".$productData['product_name']."</b>: ". $result->num_rows . "</p>";
            if( $result->num_rows > 0) {
                // initialize the variables that will print the values in the table
                $count = 0;
                $priceText = null;
                $registeredTime = null;
                echo "<table id='prices'> <tr> <th>Count</th><th>Price text</th><th>Last update</th></tr>";
                while( $row = $result->fetch_assoc()) { // loop through the current product prices records
                    if( $row['text'] == $priceText ) {
                        $count++; // if the current text is the same as the previous, just increment the counter
                    }
                    else { // if the texts are different, then print the previous (if the count was greater than 0)
                        if( $count>0 ) {
                            echo "<tr><td>". $count. "</td><td>". $priceText . "</td><td>". $registeredTime . "</td></tr>";
                        }
                        // start a new count with the new text and the new registered_time
                        $count = 1;
                        $priceText = $row['text'];
                        $registeredTime = $row['registered_time'];
                    }
                }
                // print the last record, and close the table
                echo "<tr><td>". $count. "</td><td>". $priceText . "</td><td>". $registeredTime . "</td></tr>";
                echo "</table>";
            }
            else {
                echo "0 results";
                //echo "<p>Query info:<br>" . $pricesQuery;
            }
        }
    }
    else { // the products query didn't return any results
        echo "There are no registered products to show.";
    }
    
    $mysqli->close();
    //echo "<p>The connection to the database has been closed.</p>";
?>

    </body>
</html>