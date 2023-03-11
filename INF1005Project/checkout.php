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
            
            <!-- Placeholder items, replace with shopping cart items -->
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
            
            
            <hr>
            <div class="flexcontainer">
                <h4 class="checkoutproduct"></h4>
                <h4>Total:</h4>
                <h4>$0000.00</h4>
                <!--Placeholder total value-->
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
