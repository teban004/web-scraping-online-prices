<?php

    class pricesAPI {

        function Select($productID='%', $limitResults=10) {
            require_once 'database.php';

            $prices = array();
            $query = "SELECT `price_history`.`text`, `price_history`.`registered_time`, `products`.`product_name` FROM `price_history` LEFT JOIN `products` ON `price_history`.`product_id` = `products`.`product_id` WHERE `price_history`.`product_id` LIKE :productID ORDER BY `price_history`.`registered_time` DESC LIMIT 0, :limitResults;";
            
            try {
                $statement = $db->prepare($query);
            } catch(Exception $e) {
                $message = $e->getMessage();
                echo "An error occurred: " . $message;
                exit;
            }
            
            $statement->bindValue(':productID', $productID, PDO::PARAM_STR);
            $statement->bindValue(':limitResults', $limitResults, PDO::PARAM_INT);
            $statement->execute();
            $pricesResults = $statement->fetchAll();
            $i = 0;
            $prices = array();
            foreach( $pricesResults as $priceRow) {
                $i++;
                $prices[$i] = array(
                    'registered_time' => $priceRow['registered_time'],
                    'product_name'    => $priceRow['product_name'],
                    'text'            => $priceRow['text']
                );
            }

            return json_encode($prices);
        }

    }

    $pricesAPI = new pricesAPI;
    header('Content-Type: application/json');
    echo $pricesAPI->Select();

?>