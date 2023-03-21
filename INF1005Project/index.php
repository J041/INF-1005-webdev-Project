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
        <main class="home">
<!--            <div class="container header">
                <img src="static\assets\img\home\banner.png" alt="PROMOTIONS" class="col-sm-12"/>
                <h1 class="display-4">PROMOTIONS</h1>
            </div>-->
            
            <div class="indexPromoCarousel">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                    </ol>

                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="static\assets\img\home\tortilla_new_arrival.jpg" class="d-block w-100" alt="Wild Landscape" style="width:auto; max-height:50%;"/>
                        </div>
                        <div class="carousel-item">
                            <img src="static\assets\img\home\pepsi_promo.jpg" class="d-block w-100" alt="Camera"/>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>
            </div>
<!--        <div class="container header">
            <img src="static\assets\img\home\banner.png" alt="PROMOTIONS" class="col-sm-12"/>
            <h1 class="display-4">PROMOTIONS</h1>
        </div>-->

        <br>
        <br>
        <br>

        <div class="container header" >
            <!--<img src="static\assets\img\home\banner.png" alt="CATEGORIES" class="col-sm-12"/>-->
            <h1 class="display-4">CATEGORIES</h1>
            <br>
            <br>
            <hr class="header-line">
            <br>
        </div>

        <!--    <div class="container categories"> #for reference (Category Element)    
                    <div class="row">
                        <div class="col-sm-4 categories" >
                            <div class="thumbnail d-flex align-items-center justify-content-center">
                                <a href="#">
                                    <img src="static\assets\img\home\snacks_icon.png" alt="Snacks" class="category-icon">
                                    <p class="text-center">Snacks</p>
                                </a>    
                            </div>
                        </div>
                    </div>
                </div>   -->

        <?php
        /* $category_array = array("Accessories", "Candy", "Drinks", "Household", "Snacks", "Toys", "Periodical"); */
        $columnCounter = 0;
        $category_output = "";
        $category_output .= "<div class=\"container main-categories\">";
        
        for ($i = 0; $i < count($category_array); $i++) {

            if ($columnCounter == 0) {
                $category_output .= "<div class=\"row\">";
            }
            
            $categoryImgSrc = identify_image_type($category_array[$i], "static/assets/img/home/");
            
            $category_output .= "<div class=\"col-sm-4 categories\" >" .
                    "<div class=\"thumbnail d-flex align-items-center justify-content-center categories\">" .
                    "<a href=\"catalogue.php?search_bar=".$category_array[$i]."\">" .
                    "<img src=\"" .$categoryImgSrc. "\" alt=\"" . $category_array[$i] . "\" class=\"category-icon\">" .
                    "<p class=\"text-center\">" .ucfirst($category_array[$i]). "</p>" .
                    "</a>" .
                    "</div>" .
                    "</div>";

            $columnCounter++;
            if ($columnCounter == 3) {
                $category_output .= "</div>" .
                                    "<br>";
                $columnCounter = 0;
            }
        }
        $category_output .= "</div>";
        echo $category_output;
        ?>

        <br>
        <br>

<!--        <div class="container header" >
            <img src="static\assets\img\home\banner.png" alt="DAILY DEALS" class="col-sm-12"/>
            <h1 class="display-4">DAILY DEALS</h1>
            <br>
            <br>
            <hr class="header-line">
            <br>
        </div>-->

          <?php        
//        // Global Array to store Product Catagories
//        $daily_deals_array = [];
//
//        // Create database connection.
//        $config = parse_ini_file('../private/db-config.ini');
//        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
//
//        // Check connection
//        if ($conn->connect_error) {
//            $errorMsg = "Connection failed: " . $conn->connect_error;
//            $success = false;
//        }
//
//        // Prepare, Bind & Execute SELECT statement to retrieve all active products categories:
//        $stmt = $conn->prepare("SELECT DISTINCT product_name, product_desc, price FROM Products WHERE is_active=? ORDER BY RAND() LIMIT 6");
//        $is_active = 1;
//        $stmt->bind_param("i", $is_active);
//        $stmt->execute();
//
//        // Storing Product Categories into a list
//        $result = $stmt->get_result();
//        if ($result->num_rows > 0) {
//            while ($row = $result->fetch_assoc()) {
//                array_push($daily_deals_array, $row["product_name"], $row["product_desc"], $row["price"]);
//            }
//        }
//
//        $daily_deals_output = "";
//        $daily_deals_output .= "<div class=\"container-fluid cards-row\">" .
//                "<div class=\"container main-daily-deals\">" ;
//
//        $daily_deals_columnCounter = 0;
//        for ($i = 0; $i < count($daily_deals_array); $i += 3) {
//            
//            if ($daily_deals_columnCounter == 0) {
//                $daily_deals_output .= "<div class=\"row\">";
//            }
//            
//            $product_description = $daily_deals_array[$i + 1];
//            $product_price = $daily_deals_array[$i + 2];
//            $daily_deals_output .= "<div class=\"col-lg-4 productbox\">" .
//                                "<div class=\"thumbnail productitem\">" .
//                                "<img src=\"static\assets\img\products/". $daily_deals_array[$i] . ".jpg\" alt=\"". $daily_deals_array[$i] ."\">" .
//                                "<div class=\"caption\">" .
//                                "<h3>". ucfirst($daily_deals_array[$i])."</h3>".
//                                "<p class=\"card-description\">Price: \$$product_price</p>" .
//                                "<div class=\"flex-box cartbutton\">" .    
//                                "<form action = \"#\">" .
//                                "<button class = \"view-catalogue-button\" role = \"button\">View Catalogue</button>" .
//                                "</form>" .
//                                "</div>" .
//                                "</div>" .
//                                "</div>" .
//                                "</div>";
//            
//            $daily_deals_columnCounter++;
//            if ($daily_deals_columnCounter == 3) {
//                $daily_deals_output .= "</div>" .
//                        "<br>";
//                $daily_deals_columnCounter = 0;
//            }
//}
//        $daily_deals_output .=  "</div>" .
//                                "</div>";
//        echo $daily_deals_output;
//        $conn->close();
          ?>
<!--
        <br>
        <br>-->
        <!--    <div class="container-fluid cards-row"> #for reference (Product Element)
                    <div class="container">
                        <div class="row">
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname1">
                                    <div class="caption">
                                        <h3>&lt;Product Name&gt;</h3>
                                        <p class="card-description">&lt;Product Description&gt;</p>
                                        <div class="flex-box cartbutton">
                                            <input type="image" name="submit"
                                                   src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                                   alt="Add to Cart">
                                        </div>
                                    </div>
                                </div>
                            </div>               
                        </div>
                    </div>
                </div>
        <br>
        <br>-->

        <div class="container header" >
            <!--<img src="static\assets\img\home\banner.png" alt="TRENDING ITEMS" class="col-sm-12"/>-->
            <h1 class="display-4">TRENDING ITEMS</h1>
            <br>
            <br>
            <hr class="header-line">
            <br>
        </div>

        <?php
        // Global Array to store Product Catagories
        $trending_items_array = [];

        // Create database connection.
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

        // Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        }

        // Prepare, Bind & Execute SELECT statement to retrieve all active products categories:
        $trending_items_stmt = $conn->prepare("SELECT mydb.Products.product_name, mydb.Products.price, SUM(mydb.Cart_Item.quantity) AS quantity_sold 
                                            FROM mydb.Cart_Item 
                                            INNER JOIN mydb.Order_History 
                                            ON mydb.Cart_Item.Order_History_order_id=mydb.Order_History.order_id
                                            INNER JOIN mydb.Products
                                            ON mydb.Cart_Item.Products_product_id=mydb.Products.product_id
                                            WHERE mydb.Order_History.purchased=?
                                            GROUP BY mydb.Cart_Item.Products_product_id
                                            ORDER BY quantity_sold DESC
                                            LIMIT 6;
                                            ");
        $purchased = 1;
        $trending_items_stmt->bind_param("i", $purchased);
        $trending_items_stmt->execute();

        // Storing Product Categories into a list
        $trending_items_result = $trending_items_stmt->get_result();
        if ($trending_items_result->num_rows > 0) {
            while ($row = $trending_items_result->fetch_assoc()) {
                array_push($trending_items_array, $row["product_name"], $row["price"]);
            }
        }

        $trending_items_output = "";
        $trending_items_output .= "<div class=\"container-fluid cards-row\">" .
                "<div class=\"container main-trending-items\">";

        
        $trending_items_columnCounter = 0;
        for ($i = 0; $i < count($trending_items_array); $i += 2) {
            if ($trending_items_columnCounter == 0) {
                $trending_items_output .= "<div class=\"row\">";
            }
            $product_price = $trending_items_array[$i + 1];
            
            $trendingItemsImgSrc = identify_image_type($trending_items_array[$i], "static/assets/img/products/");
            
            $trending_items_output .= "<div class=\"col-lg productbox\">" .
                    "<div class=\"thumbnail productitem\">" .
                    "<img src=\"" .$trendingItemsImgSrc. "\" alt=\"" . $trending_items_array[$i] . "\">" .
                    "<div class=\"caption\">" .
                    "<h3>" . ucfirst($trending_items_array[$i]) . "</h3>" .
                    "<p class=\"card-description\">Price: \$$product_price</p>" .
                    "<div class=\"flex-box cartbutton\">" .
                    "<form action = \"catalogue.php\">" .
                    "<input type=\"hidden\" name=\"search_bar\" value=".$trending_items_array[$i]." \>". 
                    "<button class = \"view-catalogue-button\" type=\"submit\" role = \"button\">View Catalogue</button>" .
                    "</form>" .
                    "</div>" .
                    "</div>" .
                    "</div>" .
                    "</div>";

            $trending_items_columnCounter++;
            if ($trending_items_columnCounter == 3) {
                $trending_items_output .= "</div>" .
                        "<br>";
                $trending_items_columnCounter = 0;
            }
        }
        $trending_items_output .= "</div>" .
                "</div>" .
                "</div>";
        echo $trending_items_output;
        $conn->close();
        ?>
        
    </main>

<?php
include "footer.inc.php";
?>
</body>
</html>
