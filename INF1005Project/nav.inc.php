<header>
<nav class="navbar navbar-expand-xl">
    <div class="container-fluid">
        <div class="row">
            <?php
            session_start();

            function generate_index($html_output) {

                // Generating Home Button in HTML
                $html_output .= '
                <div class="col-lg-12 col-xl-3">
                    <div class="row">
                        <div class="navb-header-row col-xs-6">
                            <a class="navbar-brand" href="index.php">MAMA Store</a>
                        </div>
                        <div class="navb-header-row col-xs-6">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Containers for next section --> 
                <div class="col-lg-12 col-xl-9">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                ';

                return $html_output;
            }

            function generate_search($html_output) {

                // Generating Search Bar in HTML
                $html_output .= '
                                    <div class="navb-search col-lg-12 col-xl-8">
                                        <form action="/catalogue.php" method="GET" title="Only alphanumeric, whitespaces and hyphen (&quot;-&quot;) are allowed.">
                                            <div class="search-container">
                                                <div class="row">
                                                    <div class="col-xs-11">
                                                        <input class="search-bar" id="search_bar" type="text" name="search_bar" placeholder="Search for Products..." data-toggle="tooltip" data-placement="bottom" aria-label="Only alphanumeric, whitespaces and hyphen (&quot;-&quot;) are allowed." maxlength = "100">
                                                    </div>
                                                    <div class="col-xs-1">
                                                        <button class="search-bar-icon btn btn-outline-primary" aria-label="search_btn" title="Click here to search for product."><i class="fa-solid fa-magnifying-glass"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                ';

                return $html_output;
            }

            function generate_catalogue($html_output) {

                // Generating opening DIV tags (div) for containers storing Navbar Options
                $html_output .= '
                                    <div class="navb-items col-lg-12 col-xl-4">
                                    <div class="navbar-nav mr-auto">
                                    <div class="navb-items-box row">
                                ';

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
                $stmt = $conn->prepare("SELECT DISTINCT product_category FROM Products WHERE is_active=? ORDER BY product_category");
                $is_active = 1;
                $stmt->bind_param("i", $is_active);
                $stmt->execute();

                // Storing Product Categories into a list
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($category_array, $row["product_category"]);
                    }
                }

                // Generating Catalogue in HTML, based on query result
                if (sizeof($category_array) == 0) {

                    // No active products in Database
                    $html_output .= '
                    
                                    <div class="item nav-item dropdown">
                                        <a class="" href="/catalogue.php" id="dropdownCatalogue" aria-label="catalogue" title="Click here to view our Product Catalogue.">
                                            <i class="fa-solid fa-book"></i>
                                            <span class="responsive_text">Catalogue</span>
                                        </a>
                                    </div>
                                    ';
                } else {
                    $html_output .= '
                    
                                    <div class="item nav-item dropdown">
                                        <a class="dropdown-toggle" href="#" id="dropdownCatalogue" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" aria-label="catalogue" title="Click here to view our Product Catalogue.">
                                            <i class="fa-solid fa-book"></i>
                                            <span class="responsive_text">Catalogue</span>
                                        </a>
                                    <div class="navb-catalogue dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCatalogue">
                                        <form action="/catalogue.php" method="GET">

                                    ';
                    
                    for ($i = 0; $i < count($category_array); $i++) {
                        $html_output .= '
                                        <div class="item row">
                                            <div class="col-xs-12">' . 
                                                "<input type=\"submit\" name=\"search_bar\" value=\"" . $category_array[$i] . "\" aria-label=\"catalogue_listing_". $category_array[$i] ."\">" .
                                            '</div>
                                        </div>
                                        ';
                    }

                    $html_output .= '
                                        <div class="dropdown-divider"></div>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <input type="submit" name="search_bar" value="All Products" aria-label="catalogue_list_all_products">
                                            </div>
                                        </div>
                                        ';

                    $html_output .= '
                                        </form>
                                    </div>
                                    ';
                    $html_output .= "</div>";
                    
                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    } else {
                        $conn->close();
                    }
                }

                

                return $html_output;
            }

            function generate_cataloguebackend($html_output) {

                // Generating Backend Catalogue in HTML
                $html_output .= '
                                <div class="item nav-item">
                                    <a href="/catalogue_backend.php" aria-label="catalogue_backend" title="Click here to view our Backend Catalogue.">
                                        <i class="fa-solid fa-book-open-reader"></i>
                                        <span class="responsive_text">Edit Catalogue</span>
                                    </a>
                                </div>
                                ';

                return $html_output;
            }

            function generate_dashboard($html_output) {

                // Generating Admin Dashboard in HTML
                $html_output .= '
                                <div class="item nav-item">
                                    <a href="/admin_dashboard.php" aria-label="admin_dashboard" title="Click here to view our Admin Dashboard.">
                                        <i class="fa-solid fa-chart-line"></i>
                                        <span class="responsive_text">Dashboard</span>
                                    </a>
                                </div>
                                ';

                return $html_output;
            }

            function generate_cart($html_output) {

                // Generating Cart in HTML
                $html_output .= '
                                <div class="item nav-item">
                                    <a href="/cart.php" aria-label="shopping_cart" title="Click here to view your shopping cart.">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                        <span class="responsive_text">Cart</span>
                                    </a>
                                </div>
                                ';

                return $html_output;
            }

            function generate_aboutus($html_output) {

                // Generating About Us in HTML
                $html_output .= '
                                <div class="item nav-item">
                                    <a href="/about_us.php" aria-label="about_us" title="Learn more about us.">
                                        <i class="fa-solid fa-circle-info"></i>
                                        <span class="responsive_text">About Us</span>
                                    </a>
                                </div>
                                ';

                return $html_output;
            }

            function generate_login($html_output) {

                // Generating Login in HTML
                $html_output .= '
                                <div class="item nav-item">
                                    <a href="/login.php" aria-label="login" title="Log into your account.">
                                        <i class="fa-solid fa-right-to-bracket"></i>
                                        <span class="responsive_text">Login</span>
                                    </a>
                                </div>
                                ';

                return $html_output;
            }

            function generate_user_details($html_output, $username) {
                $html_output .= '
                            <div class="item nav-item dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" aria-labelledby="dropdownProfile">
                                    <span>' . $username . '</span>
                                    <i class="fa-solid fa-user"></i>
                                </a>
                                <div class="navb-profile dropdown-menu dropdown-menu-right" aria-labelledby="dropdownUpdateProfile">
                                    <div class="item nav-item">
                                        <a href="/updateprofile.php">
                                            <i class="fa-solid fa-user-pen"></i>
                                            <span>Update Profile</span>
                                        </a>
                                    </div>
                                    <div class="item nav-item">
                                        <a href="/order_history.php" aria-labelledby="order_history">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            <span>Order History</span>
                                        </a>
                                    </div>
                                    <div class="item nav-item">
                                        <a href="/logout.php" aria-labelledby="logout">
                                            <i class="fa-solid fa-right-from-bracket"></i>
                                            <span>Logout</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                
                        ';

                return $html_output;
            }

            function closing_divs($html_output) {

                // Generating closing DIV tags (/div) for containers storing Navbar Options
                $html_output .= '
                                </div>
                                </div>
                                </div>
                ';
                
                        // "</div>"
                        // . "</div>"
                        // . "</div>";

                return $html_output;
            }

            $navbar_output = "";
            
            if (isset($_SESSION['priority']) && !empty($_SESSION['priority'])) {
                $username = $_SESSION['username'];
                if (($_SESSION['priority']) == 1) { // admin
                    $navbar_output = generate_index($navbar_output);
                    $navbar_output = generate_search($navbar_output);
                    $navbar_output = generate_catalogue($navbar_output);
                    $navbar_output = generate_dashboard($navbar_output);
                    $navbar_output = generate_cataloguebackend($navbar_output);
                    $navbar_output = generate_aboutus($navbar_output);
                    $navbar_output = generate_user_details($navbar_output, $username);
                    $navbar_output = closing_divs($navbar_output);
                } elseif (($_SESSION['priority']) == 2) { // staff
                    $navbar_output = generate_index($navbar_output);
                    $navbar_output = generate_search($navbar_output);
                    $navbar_output = generate_catalogue($navbar_output);
                    $navbar_output = generate_cataloguebackend($navbar_output);
                    $navbar_output = generate_aboutus($navbar_output);
                    $navbar_output = generate_user_details($navbar_output, $username);
                    $navbar_output = closing_divs($navbar_output);
                } elseif (($_SESSION['priority']) == 3) { // customer
                    $navbar_output = generate_index($navbar_output);
                    $navbar_output = generate_search($navbar_output);
                    $navbar_output = generate_catalogue($navbar_output);
                    $navbar_output = generate_aboutus($navbar_output);
                    $navbar_output = generate_cart($navbar_output);
                    $navbar_output = generate_user_details($navbar_output, $username);
                    $navbar_output = closing_divs($navbar_output);
                }
            } else {
                $navbar_output = generate_index($navbar_output);
                $navbar_output = generate_search($navbar_output);
                $navbar_output = generate_catalogue($navbar_output);
                $navbar_output = generate_aboutus($navbar_output);
                $navbar_output = generate_login($navbar_output);
                $navbar_output = closing_divs($navbar_output);
            }

            echo $navbar_output;
            ?>

        </div>
    </div>
</div>
</div>
</nav>
</header>