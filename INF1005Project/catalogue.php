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

        <div class="container">
            <?php
            // Establishing Global Variables
            global $search_query, $logic;
            $search_query = $_GET['search_bar'];

            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            }

            // Prepare, Bind & Execute SELECT statement to retrieve all active products
            $is_active = 1;

            // SQL Query Logic
            if (count($category_array) == 0) {
                // No active product categories found --> Logic = 0
                $logic = 0;
            } elseif (in_array($search_query, $category_array)) {
                // Users clicks on product category in the dropdown --> Logic = 1
                $logic = 1;
                $stmt = $conn->prepare("SELECT * FROM Products WHERE is_active=? AND product_category=?");
                $stmt->bind_param("is", $is_active, $search_query);
            } elseif ($search_query == "") {
                // Manually enters catalogue.php in URL --> Logic = 2
                $logic = 2;
                $stmt = $conn->prepare("SELECT * FROM Products WHERE is_active=?");
                $stmt->bind_param("i", $is_active);
            } else {
                // Search for specific items --> Logic = 3
                $logic = 3;
                $param = "%{$search_query}%";
                $stmt = $conn->prepare("SELECT * FROM Products WHERE is_active=? AND product_name LIKE ?");
                $stmt->bind_param("is", $is_active, $param);
            }
            $stmt->execute();

            // Output Header of Catalogue Page
            $html_output =  "<div class=\"row\">" . 
                            "<div class=\"container catalogue-display\">";
            
            if ($logic == 0) {
                $html_output .= "<h1>Please try a different search term/product category.</h1>" . 
                                "<h2>No results found! </h2>";
            } elseif ($logic == 1) {
                $html_output .= "<h1>Home/Products/" . $search_query . "</h1>" . 
                                "<h2>" . $search_query . "</h2>";
            } elseif ($logic == 2) {
                $html_output .= "<h1>Returning results for </h1>" . 
                                "<h2>All Products</h2>";
            } else {
                $html_output .= "<h1>Search result for </h1>" . 
                                "<h2>\"" . $search_query . "\"</h2>";
            }
            $html_output .= "</div></div>";

            // Defining array to store SQL output
            $results_array = [];
            
            // Output Query Results into HTML
            $result = $stmt->get_result();
            $html_output .= "<div class=\"row\">";
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Formatting of display price
                    $price_string = floatval($row["price"]);
                    $html_output .= "<div class=\"catalogue-box col-sm-12 col-md-6 col-lg-4\">"
                                    . "<div class=\"catalogue-items\">" 
                                    . "<img src=\"static/assets/img/products/" . $row["product_name"] . ".jpg\" alt=\"img_" . $row["product_name"] . "\">"
                                    . "</div>"
                                    . "<div class=\"catalogue-items\">"
                                    . "<p>" . $row["product_name"] . "</p>"
                                    . "</div>"
                                    . "<div class=\"catalogue-items\">"
                                    . "<p> SGD $" . number_format($price_string, 2, '.', '') . "</p>"
                                    . "</div>"
                                    . "<div class=\"catalogue-button\">"
                                    . "<button type=\"button\" class=\"btn btn-outline-info btn-sm\" data-toggle=\"modal\" data-target=\"#catalogue_detail_item_". $row["product_id"] ."\">"
                                    . "More Details"
                                    . "</button>"
                                    . "<button type=\"button\" class=\"btn btn-outline-success btn-sm catalogue_cart_item_". $row["product_id"] ."\">"
                                    . "+ Add to Cart <i class=\"fa-solid fa-cart-shopping\"></i>"
                                    . "</button>"
                                    . "</div>"
                                    . "</div>";
                    array_push($results_array, array($row["product_id"], $row["product_name"], $row["product_desc"], $row["product_category"], $row["quantity"], number_format($price_string, 2, '.', '')));
                }
            }
            $html_output .= "</div>";
            
            // Generate and Output product details into Modal
            for ($i = 0; $i < sizeof($results_array); $i++) {

                $html_output .= "<div class=\"product-item modal fade\" id=\"catalogue_detail_item_". $results_array[$i][0] ."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"catalogue_detail_item_". $results_array[$i][0] ."\" aria-hidden=\"true\">"
                                . "<div class=\"modal-dialog modal-xl modal-dialog-scrollable\" role=\"document\">"
                                . "<div class=\"modal-content\">"
                                . "<div class=\"modal-body\">"
                                . "<div class=\"container-fluid\">"
                        
                                . "<div class=\"product-item-btn row\">"
                                . "<button type=\"button\" data-dismiss=\"modal\"><i class=\"fa-solid fa-xmark\"></i></button>"
                                . "</div>"
                        
                                . "<div class=\"row\">"
                        
                                . "<div class=\"product-item-img col-md-12 col-lg-6\">"
                                . "<img src=\"static/assets/img/products/". $results_array[$i][1] .".jpg\" alt=\"img_". $results_array[$i][1] ."\">"
                                . "</div>"
                        
                                // Output & Styling Product Details
                                . "<div class=\"col-md-12 col-lg-6\">"
                                . "<div class=\"product-item-row row\">"
                                . "<div class=\"product-item-line col-lg-12\">"
                                . "<h1>". $results_array[$i][3] ."</h1>"
                                . "</div>"
                                . "</div>"
                                . "<div class=\"product-item-row row\">"
                                . "<div class=\"product-item-line col-lg-12\">"
                                . "<h2>". $results_array[$i][1] ."</h2>"
                                . "</div>"
                                . "</div>"
                                . "<div class=\"product-item-row row\">"
                                . "<div class=\"product-item-line col-lg-12\">"
                                . "<h3>SGD $". $results_array[$i][5] ."</h3>"
                                . "</div>"
                                . "</div>"
                                . "<div class=\"product-item-row row\">"
                                . "<div class=\"product-item-line col-lg-12\">"
                                . "<h4>". $results_array[$i][4] ." in stock</h4>"
                                . "</div>"
                                . "</div>"
                                . "<div class=\"product-item-row row\">"
                                . "<div class=\"product-item-line col-lg-12\">"
                                . "<h5>Details: </h5>"
                                . "</div>"
                                . "</div>"
                                . "<div class=\"product-item-row row\">"
                                . "<div class=\"product-item-line col-lg-12\">"
                                . "<p>". $results_array[$i][2] ."</p>"
                                . "</div>"
                                . "</div>"
                                . "<div class=\"product-item-row row\">"
                                . "<div class=\"product-item-line col-lg-12\">"
                                ."<button type=\"button\" class=\"btn btn-outline-success btn-sm catalogue_cart_item_". $row["product_id"] ."\">"
                                . "+ Add to Cart <i class=\"fa-solid fa-cart-shopping\"></i>"
                                . "</button>"
                                . "</div>"
                                . "</div>"
                        
                                // Output & Styling User Review of each product
                                . "<div class=\"product-item-row row\">"
                                . "<div class=\"product-item-line col-lg-12\">"
                                . "<h5>Reviews: </h5>"
                                . "</div>"
                                . "</div>"
                                . "<div class=\"product-item-row row\">"
                                . "<div class=\"product-item-line col-lg-12\">"
                                . "<p>To be updated</p>"
                                . "</div>"
                                . "</div>"
                                . "</div>"
                        
                                . "</div>"
                        
                                . "</div>"
                                . "</div>"
                                . "</div>"
                                . "</div>"
                                . "</div>";
            }
            
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            } else {
                $conn->close();
            }
            ?>
            
             <?php echo $html_output ?>

            <?php
            include "footer.inc.php";
            ?>
        </div>
    </body>
</html>