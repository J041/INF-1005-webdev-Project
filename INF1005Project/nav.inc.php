<header>
    <div class="container-fluid">
    <?php 
        session_start();
        $_SESSION['priority'] = 3;


        function generate_index($html_output){
            $html_output .=  "<div class=\"navb-brand\">" . 
                            "<a href=\"/index.php\">Value Convenience Store </a>" .
                            "</div>";
            return $html_output;
        }
        
        function generate_catalogue($html_output){
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

            // Storing Product Categories into a list
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($category_array, $row["product_category"]);
                }
            }
                
            $html_output .=  "<div class=\"navb-items d-none d-xl-flex\">" . 
                            "<form action=\"/catalogue.php\" method=\"GET\">" .
                            "<div class=\"search-container\">" . 
                            "<input class=\"search-bar\" id=\"search_bar\" type=\"search\" name=\"search_bar\" placeholder=\"Search for Products...\" aria-labelledby=\"search_bar\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Only alphanumeric and whitespaces allowed.\">" . 
                            "<label class=\"search-bar-icon\" for=\"search_bar\"><i class=\"fa-solid fa-magnifying-glass\"></i></label>" . 
                            "</div>" . 
                            "</form>" . 
                            "<div class=\"item\">";
            
            // Output Query Results into HTML
            if (sizeof($category_array) == 0) {
                $html_output .=  "<div>" . 
                                "<a href=\"/catalogue.php\" role=\"dropdownCatalogue\" id=\"dropdownCatalogue\">" . 
                                "Catalogue" . 
                                "</a>" . 
                                "</div>";
            } else {
                $html_output .=  "<div class=\"dropdown show\">" . 
                                "<a class=\"dropdown-toggle\" href=\"#\" role=\"dropdownCatalogue\" id=\"dropdownCatalogue\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">" . 
                                "Catalogue" . "</a>" .
                                "<div class=\"dropdown-menu dropdown-menu-right\" aria-labelledby=\"dropdownCatalogue\">" . 
                                "<form action=\"/catalogue.php\" method=\"GET\">";
                for ($i = 0; $i < count($category_array); $i++) {
                    $html_output .= "<div class=\"dropdown-item\">" . 
                                    "<input type=\"submit\" name=\"search_bar\" value=\"" . $category_array[$i] . "\" />" .
                                    "</div>";
                }

                $html_output .= "</form>" . "</div>" . "</div>";
                
                // Check connection
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                } else {
                    $conn->close();
                }
            }

            $html_output .= "</div>";

            return $html_output;
        }
        
        function generate_cart($html_output){
            $html_output .=  "<div class=\"item\">" . 
                             "<a href=\"/cart.php\">Cart</a>" .
                             "</div>";
            return $html_output;
        }
        
        function generate_aboutus($html_output){
            $html_output .=  "<div class=\"item\">" . 
                             "<a href=\"/about_us.php\">About Us</a>" .
                             "</div>";
            return $html_output;
        }
        
        function generate_login($html_output){
            $html_output .=  "<div class=\"item\">" . 
                             "<a href=\"/login.php\"><i class=\"fa-solid fa-right-to-bracket\"></i></a>" .
                             "</div>";
            return $html_output;
        }

        function generate_logout($html_output){
            return $html_output;
        }
        
        function generate_dashboard($html_output){
            return $html_output;
        }
        
        function generate_orderhistory($html_output){
            return $html_output;
        }
        
        function generate_updateprofile($html_output){
            return $html_output;
        }
        
        $navbar_output = "";
        
        if(isset($_SESSION['priority']) && !empty($_SESSION['priority'])) {
            if (($_SESSION['priority']) == 1){ // admin
                $navbar_output = generate_index($navbar_output);
                $navbar_output = generate_dashboard($navbar_output);
                $navbar_output = generate_orderhistory($navbar_output);
                $navbar_output = generate_updateprofile($navbar_output);
                $navbar_output = generate_logout($navbar_output);

            } elseif (($_SESSION['priority']) == 2){ // staff
                $navbar_output = generate_index($navbar_output);
                $navbar_output = generate_updateprofile($navbar_output);
                $navbar_output = generate_logout($navbar_output);

            } elseif (($_SESSION['priority']) == 3){ // customer
                $navbar_output = generate_index($navbar_output);
                $navbar_output = generate_catalogue($navbar_output);
                $navbar_output = generate_cart($navbar_output);
                $navbar_output = generate_aboutus($navbar_output);
                $navbar_output = generate_updateprofile($navbar_output);
                $navbar_output = generate_logout($navbar_output);
            }
        } else {
            $navbar_output = generate_index($navbar_output);
            $navbar_output = generate_catalogue($navbar_output);
            $navbar_output = generate_cart($navbar_output);
            $navbar_output = generate_aboutus($navbar_output);
            $navbar_output = generate_login($navbar_output);
        }
        
        echo $navbar_output;
    ?>
    </div>
</header>