<?php
    // from cart.php when the customer modify quantity,
    // remove an item or remove the order.
    if (isset($_POST) && !empty($_POST)) {

        session_start();
        
        // modify quantity of an item in a json file
        $customer_id = $_SESSION['customerID'];
        
        $id = $_POST['id'];
        $quantity = $_POST['quantity'];
        $jsonStr = file_get_contents("../data/order.json");
        $data = json_decode($jsonStr, true);
        $gems = $data[strval($customer_id)];
        for ($i = 0; $i < count($gems); $i++){
            if ($gems[$i]["id"] == $id) {
                // if the customer wants to remove the item
                if ($data[strval($customer_id)][$i]["quantity"] == $quantity * (-1)){
                    if (count($gems) != 1) {
                        unset($data[strval($customer_id)][$i]);
                        // transform the associative array into an array
                        $data[strval($customer_id)] = array_values($data[strval($customer_id)]);
                    } else {
                        $data = (object) null;
                    }
                    $response = "quantity null";
                } else {
                    $response = "quantity";
                    if ($data[strval($customer_id)][$i]["quantity"] + $quantity > 0){
                        $data[strval($customer_id)][$i]["quantity"] += $quantity;
                    }
                    else {
                        unset($data[strval($customer_id)][$i]);
                        // transform the associative array into an array
                        $data[strval($customer_id)] = array_values($data[strval($customer_id)]);
                    }
                }
                break;
            }
        }
        echo $response;
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents("../data/order.json", $jsonData); 
    }
?>