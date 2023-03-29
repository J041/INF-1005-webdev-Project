<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        include "header.inc.php";
        ?>
    </head>
    <body>
        <?php
        include "function.php";
        ?>
<?php
    $cardtype = $cardnumber = $cardexpiration = $securitycode = 
            $fname = $lname = $error = "";
    $success = true;

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $fname = sanitize_input($_POST["fname"]);
        if (!empty($_POST["lname"]))
        {
            $lname = sanitize_input($_POST["lname"]);
        }
        if (empty($fname))
        {
            $error .= "First name is required.<br>";
            $success = false;
        }

        if (empty($_POST["cardtype"]))
        {
            $error .= "Please enter card type<br>";
            $success = false;
        }
        else
        {
            $cardtype = $_POST["cardtype"];
            if ($cardtype != "visa" && $cardtype != "mastercard"){
                $error .= "Invalid payment card type<br>";
                $success = false;
            }
        }

        if (empty($_POST["securitycode"]))
        {
            $error .= "Please enter 3-digit card security code.<br>";
            $success = false;
        }
        else
        {
            $securitycode = $_POST["securitycode"];
            if (!is_numeric($securitycode) || strlen((string)$securitycode)!=3)
            {
                $error .= "Card security code must contain three digits.<br>";
                $success = false;
            }
        }

        if (empty($_POST["cardnumber"]))
        {
            $error .= "Please enter card number. <br>";
            $success = false;
        }
        else
        {
            $cardnumber = $_POST["cardnumber"];
            $cardnumber = str_replace("-","",$cardnumber);
            if (strlen($cardnumber)!=16 || !is_numeric($cardnumber))
            {
                $error .= "Card number must only contain 16-digits.<br>";
                $success = false;
            }

        }


        if (empty($_POST["cardexpiration"]))
        {
            $error .= "Please enter card expiration date.<br>";
            $success= false;
        }
        else
        {
            $cardexpiration = $_POST["cardexpiration"];
        }
    }
    else
    {
        include "nav.inc.php";
        echo "<main class='container'><h2>Error:</h2>";
        echo "<h4>Enter payment details <a href='payment.php'>here</a> for it to be processed</h4></main>";
        echo "<br><hr>";
        include "footer.inc.php";
        exit();
    }
    
   

    ?>
        <?php
        include "nav.inc.php";
        ?>
        <main class="container">
            <?php
                if ($success)
                {

                    $config = parse_ini_file('../private/db-config.ini');
                    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
                    if ($conn->connect_error)
                    {
                        echo "Connection failed: " . $conn->connect_error;
                    }
                    else
                    {
                        date_default_timezone_set('Asia/Singapore');
                        $dt = date('Y-m-d H:i:s');
                        $email = $_SESSION['email'];
                        
                        // echo $cardtype . "\n";
                        // echo $cardnumber . "\n";
                        // echo $dt . "\n";
                        // echo $email . "\n";
                        $updateprodquantity = $conn->prepare("UPDATE mydb.Products a, mydb.Cart_Item b
                                SET a.quantity = a.quantity - b.quantity
                                WHERE a.product_id = b.Products_product_id
                                AND b.Order_History_order_id = (SELECT b.order_id FROM mydb.Order_History b
                                WHERE b.purchased=0 AND b.Users_email=?)");
                        $updateprodquantity->bind_param("s", $email);
                        $updateprodquantity->execute();
                                
                        $stmt = $conn->prepare("UPDATE Order_History a 
                                SET a.purchased = 1, a.payment_mtd = ?, a.card_num=?, order_at=? 
                                WHERE a.Users_email = ?
                                AND a.purchased = 0");

                        $stmt->bind_param("ssss", $cardtype, $cardnumber, $dt, $email);

                        if (!$stmt->execute())
                        {
                            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                            echo "<h3>Payment Unsuccessful</h3>";
                            echo "<h4>Please return to our payment page<a href='payment.php'> here </a>to resolve the following issues:</h4>";
                            echo $errorMsg;
                        } else {
                            $stmt2 = $conn->prepare("INSERT into mydb.Order_History (Users_email, purchased) VALUES (?, 0)");
                            $stmt2->bind_param("s",$email);
                            if (!$stmt2->execute()){
                                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                                echo "<h3>Payment Unsuccessful</h3>";
                                echo "<h4>Please return to our payment page<a href='payment.php'> here </a>to resolve the following issues:</h4>";
                                echo $errorMsg;
                            } else {
                                echo "<h3>Transaction Successful!</h3>";
                                echo "<h4>Thank you for ordering with us " . $fname ." ". $lname.".</h4>";
                                echo "<a href='index.php'>Return to home.</a>";
                            }
                        }
                        $conn->close();

                    }

                }
                else
                {
                    echo "<h3>Payment Unsuccessul</h3>";
                    echo "<h4>Please return to our payment page<a href='payment.php'> here </a>to resolve the following issues:</h4>";
                    echo $error;
                }
            ?>
        </main>
        <br>
        <hr>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>