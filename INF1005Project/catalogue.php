<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
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

        <div class="container">
            <div class="row">
                <div class="container catalogue-display">
                    <?php
                    $search_query = $_GET['search_bar'];

                    // To replace array with SQL Query for unique Product Categories
                    $category_array = array("Eggs and Diary Products", "Dry and Canned Goods", "Meats and Produce", "Drinks and Alcohol", "Sweets and Snacks", "Miscellaneous");
                    if (in_array($search_query, $category_array)) {
                        echo"<h1>Home/Products/" . $search_query . "</h1>";
                        echo"<h2>" . $search_query . "</h2>";
                    } elseif ($search_query == "") {
                        echo"<h1>Returning results for </h1>";
                        echo"<h2>All Products</h2>";
                    } else {
                        echo"<h1>Search result for </h1>";
                        echo"<h2>\"" . $search_query . "\"</h2>";
                    }
                    ?>
                </div>
            </div>

            <div class="row">
                <?php
                // Defining SQL Product Columns
                global $product_id, $product_name, $product_desc, $product_category, $quantity, $price, $is_active, $created_at, $promo;
                $is_active = 1
                // Create database connection.
                $config = parse_ini_file('../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
                
                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                } else {
                    // Prepare query statement:
                    $stmt = $conn->prepare("SELECT * FROM mydb.Products WHERE is_active=?");

                    // Bind & execute the query statement:
                    $stmt->bind_param("i", $is_active);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $product_name = $row["product_name"];
                        $price = $row["price"];
                    }
                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }
                    echo "Connected successfully";
                    echo ". $product_name .";
                    echo ". $price .";
                    /*
                      <div class="catalogue-box col-sm-12 col-md-6 col-lg-4">
                      <div class="catalogue-items">
                      <img src="static/assets/img/products/ferrero_rocher.jpg" alt="img">
                      </div>
                      <div class="catalogue-items">
                      <p>Item 1</p>
                      </div>
                      <div class="catalogue-items">
                      <p>SGD $1.00</p>
                      </div>
                      <div class="catalogue-button">
                      <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#catalogue_item">
                      More Details
                      </button>
                      <button type="button" class="btn btn-outline-success btn-sm">
                      + Add to Cart <i class="fa-solid fa-cart-shopping"></i>
                      </button>
                      </div>
                      </div>
                     */
                    ?>

                    <!<!-- Testing - To be removed -->
                    <div class="catalogue-box col-sm-12 col-md-6 col-lg-4">              
                        <div class="catalogue-items">
                            <img src="static/assets/img/products/meiji_fresh_milk.jpg" alt="img">
                        </div>
                        <div class="catalogue-items">
                            <p>Item 2</p>
                        </div>
                        <div class="catalogue-items">
                            <p>SGD $2.00</p>
                        </div>
                        <div class="catalogue-button">
                            <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#catalogue_item">
                                More Details
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm">
                                + Add to Cart <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                        </div>
                    </div>

                    <div class="catalogue-box col-sm-12 col-md-6 col-lg-4">              
                        <div class="catalogue-items">
                            <img src="static/assets/img/products/salted_peanuts.jpg" alt="img">
                        </div>
                        <div class="catalogue-items">
                            <p>Item 3</p>
                        </div>
                        <div class="catalogue-items">
                            <p>SGD $3.00</p>
                        </div>
                        <div class="catalogue-button">
                            <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#catalogue_item">
                                More Details
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm">
                                + Add to Cart <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                        </div>
                    </div>

                    <div class="catalogue-box col-sm-12 col-md-6 col-lg-4">              
                        <div class="catalogue-items">
                            <img src="static/assets/img/logo.png" alt="img">
                        </div>
                        <div class="catalogue-items">
                            <p>Item 4</p>
                        </div>
                        <div class="catalogue-items">
                            <p>SGD $4.00</p>
                        </div>
                        <div class="catalogue-button">
                            <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#catalogue_item">
                                More Details
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm">
                                + Add to Cart <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                        </div>
                    </div>
                </div>



                <!-- Modal -->
                <div class="modal fade" id="catalogue_item" tabindex="-1" role="dialog" aria-labelledby="catalogue_item" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                Item Content
                            </div>
                        </div>
                    </div>
                </div>
            </div>    

    <?php
    include "footer.inc.php";
    ?>
    </body>
</html>