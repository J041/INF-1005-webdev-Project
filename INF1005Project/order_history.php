<!DOCTYPE html>
<html>
    <head>
        <?php
            include "header.inc.php";
        ?>
    </head>
    <body>
        <?php
            include "nav.inc.php";
        ?>

        <?php
        
            /*
            
            {
                orderhistory_order_id : {
                    cartitem_productid : {
                        product_name: xyz,
                        quantity: 123,
                        price: 789
                    },
                    cartitem_productid : {
                        product_name: xyz,
                        quantity: 123,
                        price: 789
                    }, ...
                }, ...
            }

            */



            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            if ($conn->connect_error)
            {
                echo "Connection failed: " . $conn->connect_error;
            }
            else
            {
                if (isset($_SESSION['priority']) && !empty($_SESSION['priority'])) {
                    $order_ids = array();
                    if (($_SESSION['priority']) == 3){
                        $getorderidsstmt = $conn->prepare("SELECT order_id FROM Order_History where Users_email=? and purchased = 1 ORDER BY order_id DESC");
                        $getorderidsstmt->bind_param("s", $_SESSION['email']);
                    } else {
                        $getorderidsstmt = $conn->prepare("SELECT order_id FROM Order_History where purchased = 1 ORDER BY order_id DESC");
                    }

                    if (!$getorderidsstmt->execute())
                    {
                        $errorMsg = "Execute failed: (" . $getorderidsstmt->errno . ") " . $getorderidsstmt->error;
                        $success = false;
                    } else {
                        $success = true;
                        $result = $getorderidsstmt->get_result();
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $final_total = 0;
                                $product_ids = array();

                                $getdetailssstmt = $conn->prepare("SELECT a.product_name, b.* FROM mydb.Products a, mydb.Cart_Item b WHERE a.product_id = b.Products_product_id AND b.Order_History_order_id = ?");
                                $getdetailssstmt->bind_param("i", $row["order_id"]);

                                if (!$getdetailssstmt->execute()) {
                                    $errorMsg = "Execute failed: (" . $getdetailssstmt->errno . ") " . $getdetailssstmt->error;
                                    $success = false;
                                } else {
                                    $success = true;
                                    $result2 = $getdetailssstmt->get_result();
                                    if ($result2->num_rows > 0) {
                                        while ($row2 = $result2->fetch_assoc()) {
                                            $product_details = array();
                                            $product_details["product_name"] = $row2["product_name"];
                                            $product_details["quantity"] = $row2["quantity"];
                                            $product_details["price"] = $row2["price"];
                                            $product_details["total"] = $row2["quantity"] * $row2["price"];
                                            $product_ids[$row2["Products_product_id"]] = $product_details;
                                        }
                                    } else {
                                        $errorMsg = "Error: No Items found in Cart";
                                        $success = false;
                                    }
                                }
                                $getdetailssstmt->close();

                                
                                $order_ids[$row["order_id"]] = $product_ids;
                            }
                        }
                    }
                    $getorderidsstmt->close();
                }
            }
            $conn->close();
        ?>

        
        <?php
            include "footer.inc.php";
        ?>
    </body>
</html>