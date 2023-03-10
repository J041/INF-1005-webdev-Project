<header>
    <div class="container-fluid">
        <!-- Default Navigation Bar -->
        <div class="navb-brand">
            <a href="/index.php">Value Convenience Store </a>
        </div>

        <div class="navb-items d-none d-xl-flex">
            <form action="/catalogue.php" method="GET">
                <div class="search-container">
                    <input class="search-bar" id="search_bar" type="search" name="search_bar" placeholder="Search for Products..." aria-labelledby="search_bar">
                    <label class="search-bar-icon" for="search_bar"><i class="fa-solid fa-magnifying-glass"></i></label>
                </div>
            </form>
            <div class="item">
                <div class="dropdown show">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownCatalogue" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Catalogue
                    </a>

                    <?php
                    // Global Array to store Product Catagories
                    global $category_array;
                    $category_array = [];

                    // Create database connection.
                    $config = parse_ini_file('../private/db-config.ini');
                    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

                    // Check connection
                    if ($conn->connect_error) {
                        $errorMsg = "Connection failed: " . $conn->connect_error;
                        $success = false;
                    }

                    // Prepare, Bind & Execute SELECT statement to retrieve all active products categories:
                    $stmt = $conn->prepare("SELECT DISTINCT product_category FROM Products WHERE is_active=?");
                    $is_active = 1;
                    $stmt->bind_param("i", $is_active);
                    $stmt->execute();

                    // Output Query Results into HTML
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            array_push($category_array, $row["product_category"]);
                        }
                    }

                    echo "<div class=\"dropdown-menu dropdown-menu-right\" aria-labelledby=\"dropdownCatalogue\">";
                    echo "<form action=\"/catalogue.php\" method=\"GET\">";
                    for ($i = 0; $i < count($category_array); $i++) {
                        echo "<div class=\"dropdown-item\">";
                        echo "<input type=\"submit\" name=\"search_bar\" value=\"". $category_array[$i] ."\" />";
                        echo "</div>";
                    }
                    echo "</form>";
                    echo "</div>";

                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    } else {
                        $conn->close();
                    }
                    ?>
                    <!--
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCatalogue">
                        <form action="/catalogue.php" method="GET">
                            <div class="dropdown-item">
                                <input type="submit" name="search_bar" value="Eggs and Diary Products" />
                            </div>
                        </form>
                    </div>
                    -->
                </div>
            </div>
            <div class="item">
                <a href="/cart.php">Cart</a>
            </div>
            <div class="item">
                <a href="/about_us.php">About Us</a>
            </div>
            <div class="item">
                <a href="/login.php"><i class="fa-solid fa-right-to-bracket"></i></a>
            </div>
        </div>

        <!-- Compressed Navigation Bar Toggle -->
        <div class="mobile-navb d-xl-none">
            <a href="#" data-toggle="modal" data-target="#navbCompress">
                <i class="fa-solid fa-bars"></i>
            </a>
        </div>

        <div class="modal fade" id="navbCompress" tabindex="-1" role="dialog" aria-labelledby="navbCompress" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="/index.php">Value Convenience Store </a>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-item">
                            <form action="/catalogue.php" method="GET">
                                <input id="search_bar_modal" type="search" name="search_bar" placeholder="Search for Products..." aria-labelledby="search_bar_compressed">
                                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </form>
                        </div>
                        <div class="modal-item">
                            <a href="/catalogue.php"><i class="fa-solid fa-shop"></i> Catalogue</a>
                        </div>
                        <div class="modal-item">
                            <a href="/cart.php"><i class="fa-solid fa-cart-plus"></i> Cart</a>
                        </div>
                        <div class="modal-item">
                            <a href="/about_us.php"><i class="fa-solid fa-address-card"></i> About Us</a>
                        </div>
                        <div class="modal-item">
                            <a href="/login.php"><i class="fa-solid fa-right-to-bracket"></i> Login/Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>