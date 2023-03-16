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
        
        <main class="container checkout">
            <div class="flexcontainer">
                <h4 class="checkoutproduct">Product</h4>
                <h4>Quantity</h4>
                <h4>Price</h4>
            </div>
            <hr>
            <!--
             Placeholder items, replace with cart items
            <div class="cartitems">
                <div class="flexcontainer">
                    <div class="checkoutproduct">
                        <img src="static/assets/img/products/Ferrero Rocher.jpg" alt="Ferrero Rocher">
                        <p>ferrero rocher</p>
                    </div>
                    <h4>1</h4>
                    <h4>$000.00</h4>
                </div>
                <div class="flexcontainer">
                    <div class="checkoutproduct">
                        <img src="static/assets/img/products/Braised Peanuts.jpg" alt="Braised Peanuts">
                        <p>braised peanuts</p>
                    </div>
                    <h4>1</h4>
                    <h4>$10.00</h4>
                </div>
            </div>
            -->
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
                $stmt = $conn->prepare("SELECT a.product_name, b.* FROM mydb.Products a, mydb.Cart_Item b
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
                    echo "<div class='cartitems'>";
                    while ($row = $result->fetch_assoc())
                    {
                        echo "<div class='flexcontainer'>";
                        echo "<div class='checkoutproduct'>
                            <img src='static/assets/img/products/".$row["product_name"].".jpg' alt='".$row["product_name"].".jpg'>
                            <p>".$row["product_name"]."</p></div>";
                        echo "<h4>".$row["quantity"]."</h4>";
                        echo "<h4>$".$row["price"]."</h4></div>";
                        $total += $row["price"] * $row["quantity"];
                    }
                    echo "</div>";
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
                <!--Calculate total value-->
            </div>
            <div class="checkoutbtns">
                <a href='catalogue.php' class='btn btn-warning'>Continue Browsing</a>
                <a href='payment.php' class='btn btn-success'>Pay Now</a>
            </div>
        </main>
         <?php
        include "footer.inc.php";
        ?>
    </body>
</html>