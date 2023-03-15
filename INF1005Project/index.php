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
        <main class="home">
            <div class="container header">
                <img src="static\assets\img\home\banner.png" alt="PROMOTIONS" class="col-sm-12"/>
                <h1 class="display-4">PROMOTIONS</h1>
            </div>

            <div class="indexPromoCarousel"
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
                        <img src="static\assets\img\home\pepsi_promo.png" class="d-block w-100" alt="Camera"/>
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

        <br>
        <br>

        <div class="container header" >
            <img src="static\assets\img\home\banner.png" alt="CATEGORIES" class="col-sm-12"/>
            <h1 class="display-4">CATEGORIES</h1>
        </div>

        <!--        <div class="container categories">    
                    <div class="row">
                        <div class="col-sm-4 categories" >
                            <div class="thumbnail d-flex align-items-center justify-content-center">
                                <a href="#">
                                    <img src="static\assets\img\home\snacks_icon.png" alt="Snacks" class="category-icon">
                                    <p class="text-center">Snacks</p>
                                </a>    
                            </div>
                        </div>
                        <div class="col-sm-4 categories">
                            <div class="thumbnail d-flex align-items-center justify-content-center">
                                <a href="#">
                                    <img src="static\assets\img\home\drinks_icon.png" alt="Drinks" class="category-icon">
                                    <p class="text-center">Drinks</p>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-4 categories">
                            <div class="thumbnail d-flex align-items-center justify-content-center">
                                <a href="#">
                                    <img src="static\assets\img\home\toys_icon.png" alt="Toys" class="category-icon">
                                    <p class="text-center">Toys</p>
                                </a>
                            </div>
                        </div>               
                    </div>
                    <div class="row">
                        <div class="col-sm-4 categories">
                            <div class="thumbnail d-flex align-items-center justify-content-center">
                                <a href="#">
                                    <img src="static\assets\img\home\accessories_icon.png" alt="Accessories" class="category-icon">
                                    <p class="text-center">Accessories</p>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-4 categories">    
                            <div class="thumbnail d-flex align-items-center justify-content-center">
                                <a href="#">
                                    <img src="static\assets\img\home\household_icon.png" alt="Household Items" class="category-icon">
                                    <p class="text-center">Household Items</p>
                                </a>
                            </div>
                        </div>
        
                        <div class="col-sm-4 categories">
                            <div class="thumbnail d-flex align-items-center justify-content-center">
                                <a href="#">
                                    <img src="static\assets\img\home\periodical_icon.png" alt="Reading Materials" class="category-icon">
                                    <p class="text-center">Reading Materials</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>   -->

        <?php
        /* $category_array = array("Accessories", "Candy", "Drinks", "Household", "Snacks", "Toys", "Periodical"); */

        $columnCounter = 0;
        $category_output = "";
        $category_output .= "<div class=\"container\">";
        for ($i = 0; $i < count($category_array); $i++) {

            if ($columnCounter == 0) {
                $category_output .= "<div class=\"row\">";
            }
            $category_output .= "<div class=\"col-sm-4 categories\" >" .
                    "<div class=\"thumbnail d-flex align-items-center justify-content-center categories\">" .
                    "<a href=\"#\">" .
                    "<img src=\"static\assets\img\home/" . $category_array[$i] . "_icon.png\" alt=\"" . $category_array[$i] . "\" class=\"category-icon\">" .
                    "<p class=\"text-center\">$category_array[$i]</p>" .
                    "</a>" .
                    "</div>" .
                    "</div>";

            $columnCounter++;
            if ($columnCounter == 3) {
                $category_output .= "</div>";
                $columnCounter = 0;
            }
        }
        $category_output .= "</div>";
        echo $category_output;
        ?>

        <br>
        <br>

        <div class="container header" >
            <img src="static\assets\img\home\banner.png" alt="DAILY DEALS" class="col-sm-12"/>
            <h1 class="display-4">DAILY DEALS</h1>
        </div>

        <?php
        // Global Array to store Product Catagories
        $daily_deals_array = [];

        // Create database connection.
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

        // Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        }

        // Prepare, Bind & Execute SELECT statement to retrieve all active products categories:
        $stmt = $conn->prepare("SELECT DISTINCT product_name, product_desc, price FROM Products WHERE is_active=? ORDER BY RAND() LIMIT 5");
        $is_active = 1;
        $stmt->bind_param("i", $is_active);
        $stmt->execute();

        // Storing Product Categories into a list
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($daily_deals_array, $row["product_name"], $row["product_desc"], $row["price"]);
            }
        }

        $daily_deals_output = "";
        $daily_deals_output .= "<div class=\"container-fluid cards-row\">" .
                "<div class=\"container\">" .
                "<div class=\"row\">";

        for ($i = 0; $i < count($daily_deals_array); $i += 3) {
            $product_description = $daily_deals_array[$i + 1];
            $product_price = $daily_deals_array[$i + 2];
            $daily_deals_output .= "<div class=\"col-lg productbox\">" .
                    "<div class=\"thumbnail productitem\">" .
                    "<img src=\"static\assets\img\home/" . $daily_deals_array[$i] . ".png\" alt=\"" . $daily_deals_array[$i] . "\">" .
                    "<div class=\"caption\">" .
                    "<h3>" . $daily_deals_array[$i] . "</h3>" .
                    "<p class=\"card-description\">Description: $product_description</p>" .
                    "<p class=\"card-description\">Price: \$$product_price</p>" .
                    "<div class=\"flex-box cartbutton\">" .
                    "<input type=\"image\" name=\"submit\" src=\"https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif\" alt=\"Add to Cart\">" .
                    "</div>" .
                    "</div>" .
                    "</div>" .
                    "</div>";
        }
        $daily_deals_output .= "</div>" .
                "</div>" .
                "</div>";
        echo $daily_deals_output;
        $conn->close();
        ?>

        <!--        <div class="container-fluid cards-row">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname2">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname3">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname4">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname4">
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
                </div>-->
        <br>
        <br>

        <div class="container header" >
            <img src="static\assets\img\home\banner.png" alt="TRENDING ITEMS" class="col-sm-12"/>
            <h1 class="display-4">TRENDING ITEMS</h1>
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
$trending_items_stmt = $conn->prepare("SELECT mydb.Sales.product_name, mydb.Products.product_desc, mydb.Sales.selling_price FROM mydb.Sales INNER JOIN mydb.Products ON mydb.Sales.product_id=mydb.Products.product_id ORDER BY mydb.Sales.units_sold DESC, mydb.Sales.revenue DESC LIMIT 10;");
$trending_items_stmt->execute();

// Storing Product Categories into a list
$trending_items_result = $trending_items_stmt->get_result();
if ($trending_items_result->num_rows > 0) {
    while ($row = $trending_items_result->fetch_assoc()) {
        array_push($trending_items_array, $row["product_name"], $row["product_desc"], $row["selling_price"]);
    }
}

$trending_items_output = "";
$trending_items_output .= "<div class=\"container-fluid cards-row\">" .
        "<div class=\"container\">";

$trending_items_columnCounter = 0;
for ($i = 0; $i < count($trending_items_array); $i += 3) {
    if ($trending_items_columnCounter == 0) {
        $trending_items_output .= "<div class=\"row\">";
    }
    $product_description = $trending_items_array[$i + 1];
    $product_price = $trending_items_array[$i + 2];
    $trending_items_output .= "<div class=\"col-lg productbox\">" .
            "<div class=\"thumbnail productitem\">" .
            "<img src=\"static\assets\img\home/" . $trending_items_array[$i] . ".png\" alt=\"" . $trending_items_array[$i] . "\">" .
            "<div class=\"caption\">" .
            "<h3>" . $trending_items_array[$i] . "</h3>" .
            "<p class=\"card-description\">Description: $product_description</p>" .
            "<p class=\"card-description\">Price: \$$product_price</p>" .
            "<div class=\"flex-box cartbutton\">" .
            "<input type=\"image\" name=\"submit\" src=\"https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif\" alt=\"Add to Cart\">" .
            "</div>" .
            "</div>" .
            "</div>" .
            "</div>";

    $trending_items_columnCounter++;
    if ($trending_items_columnCounter == 5) {
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

        <!--        <div class="container-fluid cards-row">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname2">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname3">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname4">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname4">
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
        
                <div class="container-fluid cards-row">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname2">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname3">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname4">
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
        
                            <div class="col-lg productbox">
                                <div class="thumbnail productitem">
                                    <img src="static\assets\img\home\example.jpg" alt="productname15">
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
                </div>-->
    </main>

<?php
include "footer.inc.php";
?>
</body>
</html>
