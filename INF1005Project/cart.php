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
        include "function.php";
        ?>
        
        <main class="container checkout">
            <div class="flexcontainer">
                <h4 class="checkoutproduct">Product</h4>
                <h4>Quantity</h4>
                <h4>Price</h4>
                <h4></h4>
            </div>
            <hr>
            
            <?php
                $prodid = $orderid = "";
                if (isset($_POST["remove"]))//($_SERVER["REQUEST_METHOD"] == "POST")
                {
                    $prodid = $_POST['cartprodid'];
                    $orderid = $_POST['cartorderid'];
                    $config = parse_ini_file('../private/db-config.ini');
                    $conn = new mysqli($config['servername'], $config['username'],
                    $config['password'], $config['dbname']);
                    if ($conn->connect_error)
                    {
                        echo "Connection failed: " . $conn->connect_error;
                    }
                    else
                    {
                        $removestmt = $conn->prepare("DELETE FROM mydb.Cart_Item a 
                                WHERE a.Products_product_id=? 
                                AND a.Order_History_order_id=?");
                        $removestmt->bind_param("ii", $prodid, $orderid);
                        $removestmt->execute();
                    }
                    $conn->close();
                }
                
                if (isset($_POST["add"]))
                {
                    $prodid = $_POST['cartprodid'];
                    addtocart($prodid,1);
                }
                if (isset($_POST["removecart"]))
                {
                    $prodid = $_POST['cartprodid'];
                    remoevfromcart($prodid,1);
                }
            ?>
                
            <?php         
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);
            if ($conn->connect_error)
            {
                echo "Connection failed: " . $conn->connect_error;
            }
            else
            {
                    /*read from cart where cart.orderid = orderhistory.orderid, 
                    where orderhistory.email = current logged in email, 
                    AND orderhistory.purchase is 0*/
                $stmt = $conn->prepare("SELECT a.product_name, a.quantity AS prodquantity, a.is_active,
                    b.* FROM mydb.Products a, mydb.Cart_Item b
                WHERE a.product_id = b.Products_product_id
                AND b.Order_History_order_id = (SELECT b.order_id FROM mydb.Order_History b
                WHERE b.purchased=0 AND b.Users_email=?);");
                $outputemail = $_SESSION["email"];
                $stmt->bind_param("s",$outputemail);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0)
                {
                    $total = 0;
                    $outofstock = 0;
                    echo"<div class='cartitems'>";
                    while ($row = $result->fetch_assoc())
                    {
                        echo "<form method='post' action='cart.php'>";
                        if ($row["prodquantity"] <= 0 || $row["quantity"] > $row["prodquantity"]){
                            echo "<div class='greyout'></div><h3>Out of Stock!</h3><br>";
                            $outofstock+=1;
                        }
                        elseif ($row["is_active"]<>1) {
                            echo "<div class='greyout'></div><h3>Product Not Active!</h3><br>";
                                $outofstock+=1;
                        }
                        else
                        {
                            $total += $row["price"] * $row["quantity"];
                        }
                        echo "<div class='flexcontainer'>";
                        echo "<div class='checkoutproduct'>
                            <img src='static/assets/img/products/".$row["product_name"].".jpg'"
                                . " alt='".$row["product_name"].".jpg'>
                            <p>".$row["product_name"]."</p></div>";
                        echo "<input type='hidden' name='cartprodid' value=".$row["Products_product_id"].">" 
                        ."<h4><button class='quantityupdate' name='removecart' id='reducecart'>-</button>";
                        echo $row["quantity"];
                        echo "<input type='hidden' name='cartprodid' value=".$row["Products_product_id"].">"
                         ."<button class='quantityupdate addcart' name='add'>+</button></h4>";
                        echo "<h4>$".$row["price"]."</h4>"
                                . "<input type='hidden' name='cartprodid' value=".$row["Products_product_id"].">"
                                . "<input type='hidden' name='cartorderid' value=".$row["Order_History_order_id"].">"
                                . "<h4><button type='submit' name='remove' id='deletecart'>X</button></h4></div></form>";                        
                    }
                    echo "</div>";
                    $conn->close();
                }
            }
            ?>
            <hr>
            <div class="flexcontainer">
                <h4 class="checkoutproduct"></h4>
                <h4>Total:</h4>
                <?php
                echo "<h4>$".$total."</h4>";
                ?>
                <h4></h4>
                <!--Calculate total value-->
            </div>
            <br>
            <div class="checkoutbtns">
                <a href='catalogue.php' class='btn btn-warning'>Continue Browsing</a>
                <?php
                if ($outofstock>0)
                {
                    echo "<a href='#' class='btn btn-default'>Pay Now</a>";
                }
                else
                {
                    echo "<a href='payment.php' class='btn btn-success'>Pay Now</a>";
                }
                ?>
                
            </div>
        </main>
         <?php
        include "footer.inc.php";
        ?>
    </body>
</html>