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
            echo "<div class=\"row\">";
            echo "<div class=\"container catalogue-display\">";
            if ($logic == 0) {
                echo"<h1>Please try a different search term/product category.</h1>";
                echo"<h2>No results found! </h2>";
            } elseif ($logic == 1) {
                echo"<h1>Home/Products/" . $search_query . "</h1>";
                echo"<h2>" . $search_query . "</h2>";
            } elseif ($logic == 2) {
                echo"<h1>Returning results for </h1>";
                echo"<h2>All Products</h2>";
            } else {
                echo"<h1>Search result for </h1>";
                echo"<h2>\"" . $search_query . "\"</h2>";
            }
            echo "</div>";
            echo "</div>";

            // Output Query Results into HTML
            $result = $stmt->get_result();
            echo "<div class=\"row\">";
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Formatting of display price
                    $price_string = floatval($row["price"]);

                    echo "<div class=\"catalogue-box col-sm-12 col-md-6 col-lg-4\">";
                    echo "<div class=\"catalogue-items\">";
                    echo "<img src=\"static/assets/img/products/" . $row["product_name"] . ".jpg\" alt=\"img_" . $row["product_name"] . "\">";
                    echo "</div>";
                    echo "<div class=\"catalogue-items\">";
                    echo "<p>" . $row["product_name"] . "</p>";
                    echo "</div>";
                    echo "<div class=\"catalogue-items\">";
                    echo "<p> SGD $" . number_format($price_string, 2, '.', '') . "</p>";
                    echo "</div>";
                    echo "<div class=\"catalogue-button\">";
                    echo "<button type=\"button\" class=\"btn btn-outline-info btn-sm\" data-toggle=\"modal\" data-target=\"#catalogue_item\">";
                    echo "More Details";
                    echo "</button>";
                    echo "<button type=\"button\" class=\"btn btn-outline-success btn-sm\">";
                    echo "+ Add to Cart <i class=\"fa-solid fa-cart-shopping\"></i>";
                    echo "</button>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            echo "</div>";

            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            } else {
                $conn->close();
            }
            ?>

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

            <?php
            include "footer.inc.php";
            ?>
        </div>
    </body>
</html>