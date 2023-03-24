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


        $paid_order_history = [];
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            if (isset($_SESSION['priority']) && !empty($_SESSION['priority'])) {
                $order_ids = array();
                if (($_SESSION['priority']) == 3) {
                    $getorderidsstmt = $conn->prepare("SELECT order_id, Users_email, order_at FROM Order_History where Users_email=? and purchased = 1 ORDER BY order_id DESC");
                    $getorderidsstmt->bind_param("s", $_SESSION['email']);
                } else {
                    $getorderidsstmt = $conn->prepare("SELECT order_id, Users_email, order_at FROM Order_History where purchased = 1 ORDER BY order_id DESC");
                }

                if (!$getorderidsstmt->execute()) {
                    $errorMsg = "Execute failed: (" . $getorderidsstmt->errno . ") " . $getorderidsstmt->error;
                    $success = false;
                } else {
                    $success = true;
                    $result = $getorderidsstmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                            // Censor and Stores Email into array
                            $email_name = explode("@", $row["Users_email"])[0];
                            $email_remain = explode("@", $row["Users_email"])[1];
                            $censor_email = "";

                            for ($i = 0; $i < sizeof(str_split($email_name)); $i++) {
                                if ($i < 2) {
                                    $censor_email = $censor_email . str_split($email_name)[$i];
                                } else {
                                    $censor_email = $censor_email . "*";
                                }
                            }
                            $censor_email = $censor_email . $email_remain;

                            array_push($paid_order_history, array($row["order_id"], $censor_email, $row["order_at"]));

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

            if(isset($_SESSION['username']) && !empty($_SESSION['username'])){
                if (isset($_SESSION['priority']) && !empty($_SESSION['priority']) && $_SESSION['priority'] != 2) {
                    $html_output = '
                    <div class="order-history container-fluid">
                    <div class="order-history-header row">
                        <div class="col-lg-12 col-xl-12">
                            <h1>Order History</h1>
                        </div>
                    </div>
                    ';

                    if (($_SESSION['priority']) == 3) {
    
                        $html_output .= '
    
                    <div class="order-history-data row">              
                        <table class="table table-striped table-hover table-responsive-xl">
                            <thead class="thead-light">
                                <tr>
                                    <td scope="col">Order</td>
                                    <td scope="col">Total Price</td>
                                    <td scope="col"></td>
                                </tr>
                            </thead>
                            <tbody>
    
                                ';
    
                        // Output Products into HTML Table
                        $total_prices = [];
                        foreach ($order_ids as $order_id => $value) {
                            $total_price = 0;
                            $html_output .= "<tr>";
                            $html_output .= "<td scope=\"row\">" . sprintf("%08d", $order_id) . "</td>";
    
                            foreach ($value as $sub_key => $sub_val) {
                                $total_price += $sub_val["total"];
                            }
                            array_push($total_prices, number_format($total_price, 2, '.', ''));
                            $html_output .= "<td scope=\"row\">SGD $" . number_format($total_price, 2, '.', '') . "</td>";
                            $html_output .= "<td>"
                                    . "<button type=\"button\" class=\"btn btn-outline-info btn-sm\" data-toggle=\"modal\" data-target=\"#order_details_item_" . $order_id . "\">Details</button>"
                                    . "</td>"
                                    . "</tr>";
                        }
    
                        $html_output .= '
                            </tbody>
                            </table>
                        </div>
                                ';
                    } else {
                        $html_output .= '
                    <div class="order-history-data row">              
                        <table class="table table-striped table-hover table-responsive-xl">
                            <thead class="thead-light">
                                <tr>
                                    <td scope="col">Email</td>
                                    <td scope="col">Order</td>
                                    <td scope="col">Total Price</td>
                                    <td scope="col"></td>
                                </tr>
                            </thead>
                            <tbody>
    
                                ';
    
                        // Output Order History into HTML Table
                        $total_prices = [];
                        foreach ($order_ids as $order_id => $value) {
                            $total_price = 0;
                            $html_output .= "<tr>";
                            for ($i = 0; $i < sizeof($paid_order_history); $i++) {
                                if ($paid_order_history[$i][0] == $order_id) {
                                    $html_output .= "<td scope=\"row\">" . $paid_order_history[$i][1] . "</td>";
                                    break;
                                }
                            }
                            $html_output .= "<td scope=\"row\">" . sprintf("%08d", $order_id) . "</td>";
    
                            foreach ($value as $sub_key => $sub_val) {
                                $total_price += $sub_val["total"];
                            }
                            array_push($total_prices, number_format($total_price, 2, '.', ''));
                            $html_output .= "<td scope=\"row\">SGD $" . number_format($total_price, 2, '.', '') . "</td>";
                            $html_output .= "<td>"
                                    . "<button type=\"button\" class=\"btn btn-outline-info btn-sm\" data-toggle=\"modal\" data-target=\"#order_details_item_" . $order_id . "\">Details</button>"
                                    . "</td>"
                                    . "</tr>";
                        }
    
                        $html_output .= '
                        </tbody>
                        </table>
                    </div>
                                ';
                    }
    
                    foreach ($order_ids as $order_id => $value) {
                        $total_price = 0;
                        $html_output .= "<div aria-hidden=\"true\" aria-labelledby=\"order_details_item_" . $order_id . "\" class=\"order-history-item modal fade\" id=\"order_details_item_" . $order_id . "\" role=\"dialog\" tabindex=\"-1\">";
    
                        $html_output .= '
                        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="order-history-item-btn row">
                                            <button data-dismiss="modal" type="button"><i class="fa-solid fa-xmark"></i></button>
                                        </div>
                        <div class="order-history-data-details row">
                                ';
                        
                        $html_output .= "<h1>Order ID: #". sprintf("%08d", $order_id) ."</h1>";
                        
                        $html_output .= '
                        </div>
                        <div class="order-history-data-details row">              
                            <table class="table table-hover table-responsive-xl">
                                <thead class="thead-light">
                                ';
                        
                        for ($i = 0; $i < sizeof($paid_order_history); $i++) {
                            if ($paid_order_history[$i][0] == $order_id) {
                                $html_output .= "<tr>";
                                $html_output .= "<th scope=\"col\" colspan=\"2\">Order At: " . $paid_order_history[$i][2] . "</th>";
                                $html_output .= "<th scope=\"col\" colspan=\"2\">Sub-Total: SGD $" . $total_prices[$i] . "</th>";
                                $html_output .= "<tr>";
                            }
                        }
    
                        $html_output .= '
                                            <tr>
                                                <td scope="col">Product Name</td>
                                                <td scope="col">Quantity</td>
                                                <td scope="col">Price</td>
                                                <td scope="col">Total</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                ';
    
                        foreach ($value as $sub_key => $sub_val) {
                            for ($i = 0; $i < sizeof($paid_order_history); $i++) {
                                if ($paid_order_history[$i][0] == $order_id) {
                                    $html_output .= "<tr>";
                                    $html_output .= "<td scope=\"row\">" . $sub_val["product_name"] . "</td>";
                                    $html_output .= "<td scope=\"row\">" . $sub_val["quantity"] . "</td>";
                                    $html_output .= "<td scope=\"row\">SGD $" . number_format($sub_val["price"], 2, '.', '') . "</td>";
                                    $html_output .= "<td scope=\"row\">SGD $" . number_format($sub_val["total"], 2, '.', '') . "</td>";
                                    $html_output .= "</tr>";
                                }
                            }
                        }
                        $html_output .= '
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                                ';
                    }
    
                    echo $html_output;
                }

            } else {
                $html_output = "<div class=\"container-fluid\" role=\"alert\">"
                        . "<div class=\"row\">"
                        . "<div class=\"output-msg card\">"
                        . "<div class=\"card-body\">"
                        . "<p class=\"text-danger\">You must be logged in to access this page</p>"
                        . "</div>"
                        . "</div>"
                        . "</div>"
                        . "</div>";
                echo $html_output;
            }
            ?>
        </div>


        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>