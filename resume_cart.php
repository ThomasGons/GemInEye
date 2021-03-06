<?php
    session_start();
    $current_uri = $_SERVER["REQUEST_URI"];
    if (!isset($_SESSION['referrer']) || empty($_SESSION['referrer']) || $_SESSION['referrer'] !== "/cart.php") {
        $_SESSION['referrer'] = $current_uri;
        header("Location: php/error_page.php");
    } else {
        $previous_uri = $_SESSION['referrer'];
        $_SESSION['referrer'] = $current_uri;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Confirm Cart - Gem In Eye</title>
    <meta charset="UTF-8">
    <meta name="description" content="Gemstones online shop">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/styles.css" rel="stylesheet" type="text/css">
    <link href="/css/styles_cart.css" rel="stylesheet" type="text/css">
    <script src="/js/navbar.js" defer></script>
    <script src="/js/connected.js" defer></script>
    <script src="/js/order.js" defer></script>
</head>
<body>
    <?php include "php/header.php"; ?>
    <main>
        <?php include "php/side_bar.php"; ?>
        <div id="page-content">
            <h1 id="resume-title">Order resume</h1>
            <div id='order-content'>
                <?php $jsonOrder = file_get_contents("data/order.json");
                $order = json_decode($jsonOrder, true)[strval($_SESSION['customerID'])]; ?>
                <!-- empty cart if order.json is empty -->
                <?php
                    if ($order != array()) {
                        $jsonStock = file_get_contents("data/stock.json");
                        $stock = json_decode($jsonStock, true); ?>
                <div id="resume-container">
                <div id="resume">
                    <p>Total : $
                        <?php 
                            $totalprice = 0;
                            for($i = 0; $i < count($order); $i++) {
                                $totalprice += $order[$i]['price']*$order[$i]["quantity"];
                            }
                            echo strval($totalprice);
                        ?>
                    </p> 
                    <p style="font-size:small">Including TVA = <?php echo 0.20*$totalprice;?> $ ( 20% )</p> 
                </div>
                <a href="/ticket.php" id="link-resume" class="order-button"><div id="buy-button">Buy Now !</div></a>
                </div>
                <table>
                    <thead>
                        <th>Photo</th>
                        <th style='display: none'>Id</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <!-- display stock if customer is an admin -->
                        <?php 
                            if (isset($_SESSION['admin']) && $_SESSION['admin']) { ?>
                        <th>Stock</th>
                        <?php 
                            } else { ?>
                        <th style='display: none;'>Stock</th>
                        <?php }?>
                        <th>Price</th>
                    </thead>
                    <tbody>
                        <!-- search main data about each item (img path, name, quantity) -->
                        <?php 
                            for($i = 0; $i < count($order); $i++) {
                                $id = $order[$i]["id"];
                                $stockQuantity = $stock[strval($id[0])][$id[1] - 1]["quantity"]; ?>
                        <tr>
                            <td style='width: 30%;'><img style='padding: 5px 0;' src='<?=$order[$i]['img']?>' width='225' height='225'></td>
                            <td style='display: none' class='gem-id'><?=$order[$i]['id']?></td>
                            <td style='width: 35%;'><?=$order[$i]['name']?></td>
                            <td style='width: 25%;' class='quantity'>
                                <div class='quantity-div'>
                                    <p id="quantity-span"><?=$order[$i]['quantity']?></p>
                                </div>
                            </td>
                            <?php 
                                if (isset($_SESSION['admin']) && $_SESSION['admin']) { ?>
                            <td class='stock'><?=$stockQuantity?></td>
                            <?php 
                                } else { ?>
                            <td class='stock' style='display: none'><?=$stockQuantity?></td>
                            <?php } ?>
                            <td style='width: 20%;'><div><?=$order[$i]['price']?>$</div><div style="font-size:medium">(TVA = <?=0.20 * $order[$i]['price']?>$)</div></td>

                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php 
                    } else { ?>
                <h1 style='margin: 10% auto;'>Your cart is empty</h1>
                <?php } ?>
            </div>
        </div>
    </main>
    <?php include "commons/footer.html"; ?>
</body>
</html>