<!DOCTYPE html>
<html lang="en">
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

        <?php

        function check_if_bought_before($product_id) {
            // Create database connection
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                $order_ids = array();
                // Prepare the statement:
                $orderidstmt = $conn->prepare("SELECT order_id FROM Order_History where Users_email = ? and purchased = ?");
                // Bind & execute the query statement:
                $Users_email = $_SESSION['email'];
                $purchased = 1;
                $orderidstmt->bind_param("si", $Users_email, $purchased);
                if (!$orderidstmt->execute()) {
                    $errorMsg = "Execute failed: (" . $orderidstmt->errno . ") " . $orderidstmt->error;
                    $success = false;
                } else {
                    $result = $orderidstmt->get_result();
                    if ($result->num_rows > 0) {

                        while ($row = $result->fetch_assoc()) {
                            array_push($order_ids, $row["order_id"]);
                        }
                    } else {
                        $errorMsg = "less than 1 result";
                        $success = false;
                    }
                }
                $orderidstmt->close();

                $bought_productid = array();
                foreach ($order_ids as $order_id) {
                    // echo "$order_id <br>";
                    $productidstmt = $conn->prepare("SELECT Products_product_id FROM Cart_Item where Order_History_order_id = ?");
                    $productidstmt->bind_param("i", $order_id);
                    if (!$productidstmt->execute()) {
                        $errorMsg = "Execute failed: (" . $productidstmt->errno . ") " . $productidstmt->error;
                        $success = false;
                    } else {
                        $result = $productidstmt->get_result();
                        if ($result->num_rows > 0) {


                            while ($row = $result->fetch_assoc()) {
                                array_push($bought_productid, $row["Products_product_id"]);
                            }

                            // $row = $result->fetch_assoc();
                            // array_push($bought_productid, $row["Products_product_id"]);
                        } else {
                            $errorMsg = "less than 1 result";
                            $success = false;
                        }
                    }
                    $productidstmt->close();
                }

                if (in_array($product_id, $bought_productid)) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        function addreview($product_id, $comment, $ratings) {
            if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {

                // Create database connection.
                $config = parse_ini_file('../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                } else {
                    mysqli_error(MYSQLI_ERROR_OFF);
                    ini_set("display_errors", 1);
                    error_reporting(E_ALL);

                    // Prepare the statement:
                    $addreviewstmt = $conn->prepare("INSERT INTO Feedback (Products_product_id, Users_email, comments, ratings) VALUES (?,?,?,?)");

                    // Bind & execute the query statement:
                    $user_email = $_SESSION['email'];
                    $addreviewstmt->bind_param("issi", $product_id, $user_email, $comment, $ratings);
                    if (!$addreviewstmt->execute()) {
                        $errorMsg = "Execute failed: (" . $addreviewstmt->errno . ") " . $addreviewstmt->error;
                        $success = false;
                    } else {
                        echo "comment added";
                    }
                    $addreviewstmt->close();
                }
                $conn->close();
            } else {
                header("Location: login.php");
            }
        }

        function getusername($email) {
            // Create database connection
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                $getusernametmt = $conn->prepare("SELECT username FROM Users where email = ?");
                $getusernametmt->bind_param("s", $email);
                if (!$getusernametmt->execute()) {
                    $errorMsg = "Execute failed: (" . $getusernametmt->errno . ") " . $getusernametmt->error;
                    $success = false;
                } else {
                    $success = true;
                    $result = $getusernametmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $username = $row["username"];
                    } else {
                        $username = 'user does not exist';
                    }
                    return $username;
                }
            }
        }

        function editreviews($product_id, $comment) {
            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                $updatereviewstmt = $conn->prepare("UPDATE Feedback SET comments = ? WHERE Products_product_id = ? AND Users_email = ?");
                // Bind & execute the query statement:
                $updatereviewstmt->bind_param("sis", $comment, $product_id, $_SESSION['email']);
                if (!$updatereviewstmt->execute()) {
                    $errorMsg = "Execute failed: (" . $updatereviewstmt->errno . ") " . $updatereviewstmt->error;
                    $success = false;
                } else {
                    $success = true;
                }
                $updatereviewstmt->close();
            }
        }

        function getreviews($product_id) {
            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                $reviews_full = array();
                // Prepare the statement:
                $getreviewstmt = $conn->prepare("SELECT * FROM Feedback where Products_product_id = ?");
                // Bind & execute the query statement:
                $getreviewstmt->bind_param("i", $product_id);
                if (!$getreviewstmt->execute()) {
                    $errorMsg = "Execute failed: (" . $getreviewstmt->errno . ") " . $getreviewstmt->error;
                    $success = false;
                } else {
                    $success = true;
                    $result = $getreviewstmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $user_email = $row["Users_email"];
                            $ratings = $row["ratings"];
                            $user_comment = $row["comments"];
                            $username = getusername($user_email);
                            array_push($reviews_full, array($username, $ratings, $user_comment));
                        }
                    }
                    return $reviews_full;
                }
                $getreviewstmt->close();
            }
            $conn->close();
        }

        function stars_generator($decimal_rating, $int_average_rating) {
            $html_output = "";

            if ($decimal_rating == 0) {
                // Filled Stars 
                for ($k = 0; $k < ($int_average_rating); $k++) {
                    $html_output .= "<i class=\"fa-solid fa-star\"></i>";
                }

                // Unfilled Stars 
                for ($k = 0; $k < (5 - $int_average_rating); $k++) {
                    $html_output .= "<i class=\"fa-regular fa-star\"></i>";
                }
            } else {
                // Filled Stars 
                for ($k = 0; $k < ($int_average_rating); $k++) {
                    $html_output .= "<i class=\"fa-solid fa-star\"></i>";
                }
                // Half-Filled Stars
                $html_output .= "<i class=\"fa-solid fa-star-half-stroke\"></i>";

                // Unfilled Stars 
                for ($k = 0; $k < (5 - 1 - $int_average_rating); $k++) {
                    $html_output .= "<i class=\"fa-regular fa-star\"></i>";
                }
            }
            return $html_output;
        }

        function output_messages($success_msg, $error_msg) {
            // Display Success Messages
            for ($i = 0; $i < sizeof($error_msg); $i++) {
                $html_output .= "<div class=\"row\">"
                        . "<div class=\"output-msg card\">"
                        . "<div class=\"card-body\">"
                        . "<p class=\"text-danger\">" . $error_msg[$i] . "</p>"
                        . "</div>"
                        . "</div>"
                        . "</div>";
            }

            // Display Success Messages
            for ($i = 0; $i < sizeof($success_msg); $i++) {
                $html_output = "<div class=\"container\">"
                        . "<div class=\"row\">"
                        . "<div class=\"output-msg card\">"
                        . "<div class=\"card-body\">"
                        . "<p class=\"text-success\">" . $success_msg[$i] . "</p>"
                        . "</div>"
                        . "</div>"
                        . "</div>"
                        . "</div>";
            }
            echo $html_output;
        }

        function check_if_review_exist($email) {
            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                $review_exist = array();
                // Prepare the statement:
                $check_review_exist_stmt = $conn->prepare("SELECT Products_product_id FROM Feedback where Users_email = ?");
                // Bind & execute the query statement:
                $check_review_exist_stmt->bind_param("s", $email);
                if (!$check_review_exist_stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $check_review_exist_stmt->errno . ") " . $check_review_exist_stmt->error;
                    $success = false;
                } else {
                    $success = true;
                    $result = $check_review_exist_stmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $product_id = $row["Products_product_id"];
                            array_push($review_exist, $product_id);
                        }
                    }
                    return $review_exist;
                }
                $check_review_exist_stmt->close();
            }
            $conn->close();
        }
        ?>

        <div class="container">

            <?php
            // Establishing Global Variables
            global $search_query;
            $search_query = sanitize_input($_GET['search_bar']);

            // Search Bar Validation
            $validation = sanitize_regex_input($search_query);

            // Defining array to store SQL output & Output Messages.
            $results_array = [];
            $error_msg = [];
            $success_msg = [];

            // Verifies that no errors encounterd during input validation
            if ($validation == "Unidentified Character") {
                array_push($error_msg, "$validation detected. Please only enter alphabets, spaces and hyphen (\"-\").");
            } else {
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
                if (sizeof($category_array) == 0) {
                    // No active product categories found --> Logic = 0
                    $logic = 0;
                } elseif (in_array($search_query, $category_array)) {
                    // Users clicks on product category in the dropdown --> Logic = 1
                    $logic = 1;
                    $stmt = $conn->prepare("SELECT * FROM Products WHERE is_active=? AND product_category=?");
                    $stmt->bind_param("is", $is_active, $search_query);
                } elseif ($search_query == "All Products") {
                    // clicks on Show all on catalogue.php in dropdown --> Logic = 2
                    $logic = 2;
                    $stmt = $conn->prepare("SELECT * FROM Products WHERE is_active=?");
                    $stmt->bind_param("i", $is_active);
                } elseif ($search_query == "") {
                    // Manually enters catalogue.php in URL --> Logic = 2
                    $logic = 2;
                    $stmt = $conn->prepare("SELECT * FROM Products WHERE is_active=?");
                    $stmt->bind_param("i", $is_active);
                } else {
                    // Search for specific items --> Logic = 3
                    $logic = 3;
                    $param = "{$search_query}%";
                    $stmt = $conn->prepare("SELECT * FROM Products WHERE is_active=? AND product_name LIKE ?");
                    $stmt->bind_param("is", $is_active, $param);
                }
                $stmt->execute();

                // Storing SQL output into an array
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Formatting of display price
                        $price_string = floatval($row["price"]);
                        array_push($results_array, array($row["product_id"], $row["product_name"], $row["product_desc"], $row["product_category"], $row["quantity"], number_format($price_string, 2, '.', '')));
                    }
                }

                // Check connection
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                } else {
                    $conn->close();
                }
            }

            // Checks if "Add to Cart" button has been clicked
            for ($i = 0; $i < sizeof($results_array); $i++) {
                // Storing Name of "Add to Cart" & "Save" review buttons
                $add_cart_btn = "add_cart_item_" . $results_array[$i][0];
                $add_review_btn = "add_review_" . $results_array[$i][0];

                // Identifies which "Add to Cart" button is called
                if (isset($_POST[$add_cart_btn])) {

                    // Updates cart and redirects to 'cart.php'
                    addtocart($results_array[$i][0], 1);
                    redirect_page('cart.php');
                }

                // Identifies which "Save" review button is called
                if (isset($_POST[$add_review_btn])) {
                    // Defining Form Variables for "Save" review form
                    $user_review_rating = sanitize_input($_POST["rating"]);
                    $user_review_text = sanitize_input($_POST["review_text"]);

                    $accepted_ratings = array(1, 2, 3, 4, 5);

                    // Fields Input Validation
                    if (!in_array($user_review_rating, $accepted_ratings)) {
                        $msg = "Please selected a rating provided in the Reviews section.";
                        array_push($error_msg, $msg);
                    }

                    if ($user_review_text == "") {
                        $msg = "Please enter a review in the Reviews section.";
                        array_push($error_msg, $msg);
                    } else if (sanitize_regex_desc($user_review_text) == "Unidentified Character") {
                        $msg = "Only Alphanumeric characters, whitespaces, commas (,), full-stops (.), exclaimation marks (!) and hyphens (-) are accepted in Reviews.";
                        array_push($error_msg, $msg);
                    }

                    // Validates if customer has purchase the product
                    if (!check_if_bought_before($results_array[$i][0])) {
                        $msg = "Please purchase the product before leaving a review.";
                        array_push($error_msg, $msg);
                    }
                    
                    // Ensure that user does not have an existing review of the product
                    if (in_array($results_array[$i][0], check_if_review_exist($_SESSION['email']))) {
                        $msg = "You have an existing review of the product. Please consider editing your review.";
                        array_push($error_msg, $msg);
                    }

                    if (!empty($error_msg)) {
                        // Display Error/Success Messages
                        output_messages($success_msg, $error_msg);
                    } else {
                        addreview($results_array[$i][0], $user_review_text, $user_review_rating);

                        $msg = "You have sucessfully added a review for " . $results_array[$i][1] . ".";
                        array_push($success_msg, $msg);

                        // Display Error/Success Messages
                        output_messages($success_msg, $error_msg);
                    }
                }
            }

            // Output Header of Catalogue Page
            $html_output = "";

            if ($logic == null) {
                // Display Error/Success Messages
                output_messages($success_msg, $error_msg);

                $html_output .= "<div class=\"row\">"
                        . "<div class=\"container catalogue-display\">"
                        . "<h1>Search result for </h1>"
                        . "<h2>\"" . $search_query . "\"</h2>"
                        . "</div>"
                        . "</div>";
            } elseif ($logic == 0) {
                $html_output .= "<div class=\"row\">"
                        . "<div class=\"container catalogue-display\">"
                        . "<h1>Please try a different search term/product category.</h1>"
                        . "<h2>No results found!</h2>"
                        . "</div>"
                        . "</div>";
            } elseif ($logic == 1) {
                $html_output .= "<div class=\"row\">"
                        . "<div class=\"container catalogue-display\">"
                        . "<h1>Home/Products/" . $search_query . "</h1>"
                        . "<h2>" . $search_query . "</h2>"
                        . "</div>"
                        . "</div>";
            } elseif ($logic == 2) {
                $html_output .= "<div class=\"row\">"
                        . "<div class=\"container catalogue-display\">"
                        . "<h1>Returning results for </h1>"
                        . "<h2>All Products</h2>"
                        . "</div>"
                        . "</div>";
            } else {
                $html_output .= "<div class=\"row\">"
                        . "<div class=\"container catalogue-display\">"
                        . "<h1>Search result for </h1>"
                        . "<h2>\"" . $search_query . "\"</h2>"
                        . "</div>"
                        . "</div>";
            }

            // Output Query Results into HTML
            $html_output .= "<div class=\"row\">";
            for ($i = 0; $i < sizeof($results_array); $i++) {
                $html_output .= "<div class=\"catalogue-box col-sm-12 col-md-6 col-lg-4\">"
                        . "<div class=\"catalogue-items\">"
                        . "<img src=\"" . identify_image_type($results_array[$i][1], "static/assets/img/products/") . "\" alt=\"img_" . $results_array[$i][1] . "\">"
                        . "</div>"
                        . "<div class=\"catalogue-items\">"
                        . "<p>" . $results_array[$i][1] . "</p>"
                        . "</div>"
                        . "<div class=\"catalogue-items\">"
                        . "<p> SGD $" . $results_array[$i][5] . "</p>"
                        . "</div>"
                        . "<form action=\"/catalogue.php\" method=\"POST\">"
                        . "<div class=\"catalogue-button\">"
                        . "<button type=\"button\" class=\"btn btn-outline-info btn-sm\" data-toggle=\"modal\" data-target=\"#catalogue_detail_item_" . $results_array[$i][0] . "\">"
                        . "More Details"
                        . "</button>"
                        . "<button type=\"submit\" class=\"btn btn-outline-success btn-sm" . $results_array[$i][0] . "\" name=\"add_cart_item_" . $results_array[$i][0] . "\">"
                        . "+ Add to Cart <i class=\"fa-solid fa-cart-shopping\"></i>"
                        . "</button>"
                        . "</div>"
                        . "</form>"
                        . "</div>";
            }
            $html_output .= "</div>";

            // Generate and Output product details into Modal
            for ($i = 0; $i < sizeof($results_array); $i++) {

                $html_output .= "<div class=\"product-item modal fade\" id=\"catalogue_detail_item_" . $results_array[$i][0] . "\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"catalogue_detail_item_" . $results_array[$i][0] . "\" aria-hidden=\"true\">"
                        . "<div class=\"modal-dialog modal-xl modal-dialog-scrollable\" role=\"document\">"
                        . "<div class=\"modal-content\">"
                        . "<div class=\"modal-body\">"
                        . "<div class=\"container-fluid\">"
                        . "<div class=\"product-item-btn row\">"
                        . "<button type=\"button\" data-dismiss=\"modal\"><i class=\"fa-solid fa-xmark\"></i></button>"
                        . "</div>"
                        . "<div class=\"row\">"
                        . "<div class=\"product-item-img col-md-12 col-lg-6\">"
                        . "<img src=\"" . identify_image_type($results_array[$i][1], "static/assets/img/products/") . "\" alt=\"img_" . $results_array[$i][1] . "\">"
                        . "</div>"

                        // Output & Styling Product Details
                        . "<div class=\"col-md-12 col-lg-6\">"
                        . "<div class=\"product-item-row row\">"
                        . "<div class=\"product-item-line col-lg-12\">"
                        . "<h1>" . $results_array[$i][3] . "</h1>"
                        . "</div>"
                        . "</div>"
                        . "<div class=\"product-item-row row\">"
                        . "<div class=\"product-item-line col-lg-12\">"
                        . "<h2>" . $results_array[$i][1] . "</h2>"
                        . "</div>"
                        . "</div>"
                        . "<div class=\"product-item-row row\">"
                        . "<div class=\"product-item-line col-lg-12\">"
                        . "<h3>SGD $" . $results_array[$i][5] . "</h3>"
                        . "</div>"
                        . "</div>"
                        . "<div class=\"product-item-row row\">"
                        . "<div class=\"product-item-line col-lg-12\">"
                        . "<h4>" . $results_array[$i][4] . " in stock</h4>"
                        . "</div>"
                        . "</div>"
                        . "<div class=\"product-item-row row\">"
                        . "<div class=\"product-item-line col-lg-12\">"
                        . "<h5>Details: </h5>"
                        . "</div>"
                        . "</div>"
                        . "<div class=\"product-item-row row\">"
                        . "<div class=\"product-item-line col-lg-12\">"
                        . "<p>" . $results_array[$i][2] . "</p>"
                        . "</div>"
                        . "</div>"
                        . "<div class=\"product-item-row row\">"
                        . "<div class=\"product-item-line col-lg-12\">"
                        . "<form action=\"/catalogue.php\" method=\"POST\">"
                        . "<button type=\"submit\" class=\"btn btn-outline-success btn-sm catalogue_cart_item_" . $results_array[$i][0] . "\" name=\"add_cart_item_" . $results_array[$i][0] . "\">"
                        . "+ Add to Cart <i class=\"fa-solid fa-cart-shopping\"></i>"
                        . "</button>"
                        . "</form>"
                        . "</div>"
                        . "</div>";

                // Adding Reviews for Each Product
                $html_output .= "<div class=\"review-item-row row\">"
                        . "<div class=\"review-header-button-row col-md-12 col-xl-12\">";


                if (check_if_bought_before($results_array[$i][0])) {
                    $html_output .= "<button class=\"btn btn-outline-primary new-review-add\" tabindex=\"0\" aria-pressed=\"false\" title=\"Leave a review\"><i class=\"fa-solid fa-plus\"></i>&nbsp; Add </button>"
                            . "<button class=\"btn btn-outline-secondary new-review-add-close d-none\" tabindex=\"0\" aria-pressed=\"false\"><i class=\"fa-solid fa-xmark\"></i>&nbsp; Close </button>";
                } else {
                    $html_output .= "<button class=\"btn btn-outline-secondary new-review-add disabled\" tabindex=\"0\" aria-disabled=\"true\"><i class=\"fa-solid fa-plus\"></i>&nbsp; Add </button>";
                }

                $html_output .= "</div>"
                        . "</div>";

                $html_output .= '
                    <div class="review-item-row row d-none">
                        <form action="catalogue.php" method="POST" enctype="multipart/form-data">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="review-row-form col-lg-12 col-xl-12">
                                            <h6>Please let us know how you found the product.</h6>
                                        </div>
                                        <div class="review-row-form col-lg-12 col-xl-12">
                                            <label class="">1 <i class="fa-solid fa-star"></i></label>
                                            <input class="" type="radio" name="rating" value="1" required>
                                            <label class="">2 <i class="fa-solid fa-star"></i></label>
                                            <input class="" type="radio" name="rating" value="2" required>
                                            <label class="">3 <i class="fa-solid fa-star"></i></label>
                                            <input class="" type="radio" name="rating" value="3" required>
                                            <label class="">4 <i class="fa-solid fa-star"></i></label>
                                            <input class="" type="radio" name="rating" value="4" required>
                                            <label class="">5 <i class="fa-solid fa-star"></i></label>
                                            <input class="" type="radio" name="rating" value="5" required>
                                        </div>
                                        <div class="review-row-form col-lg-12 col-xl-12">
                                            <label class="">Review: </label>
                                            <textarea name="review_text" placeholder="Please let us know how you found the product." maxlength="150" required ></textarea>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="review-header-button-row review-row-form col-md-12 col-lg-12">
                                        ';
                $html_output .= "<button class=\"btn btn-outline-success\" tabindex=\"0\" name=\"add_review_" . $results_array[$i][0] . "\" aria-pressed=\"false\"><i class=\"fa-solid fa-floppy-disk\"></i>&nbsp; Save </button>";

                $html_output .= '                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                            ';

                // Displaying Reviews for Each Product
                $html_output .= "<div class=\"review-item-row row\">";

                // Ratings Calculation
                $reviews = getreviews($results_array[$i][0]);
                $total_rating = 0;
                for ($k = 0; $k < sizeof($reviews); $k++) {
                    $total_rating += $reviews[$k][1];
                }
                $review_count = sizeof($reviews);

                if ($review_count == 0) {
                    $html_output .= "<div class=\"product-item-line review-stars col-lg-12\">"
                            . "<h5>Reviews: </h5>"
                            . "</div>"
                            . "</div>"
                            . "<div class=\"product-item-row row\">"
                            . "<div class=\"product-item-line col-lg-12\">"
                            . "<p>No Reviews. Be the first to leave some feedback regarding the product!</p>"
                            . "</div>"
                            . "</div>";
                } else {
                    if (($total_rating / $review_count) == 5) {
                        $int_average_rating = $total_rating / $review_count;
                        $decimal_rating = 0;
                    } else {
                        $int_average_rating = ($total_rating / $review_count) % 5;
                        $decimal_rating = $total_rating - ($int_average_rating * $review_count);
                    }

                    $html_output .= "<div class=\"product-item-line review-stars col-sm-8 col-md-8 col-lg-8\">"
                            . "<h5>" . number_format($total_rating / $review_count, 1, '.', '') . "</h5>"
                            . stars_generator($decimal_rating, $int_average_rating)
                            . "</div>"
                            . "<div class=\"product-item-line col-sm-4 col-md-4 col-lg-4\">"
                            . "<h6>" . $review_count . " Reviews </h6>"
                            . "</div>"
                            . "</div>";

                    // Output Reviews into HTML
                    for ($k = 0; $k < sizeof($reviews); $k++) {
                        $html_output .= "<div class=\"review-item-row row\">"
                                . "<div class=\"card\">"
                                . "<div class=\"card-body\">"
                                . "<div class=\"review-item-line row\">"
                                . "<div class=\"col-sm-8 col-md-8 col-lg-8\">"
                                . "<p>" . $reviews[$k][0] . "</p>"
                                . "</div>"
                                . "<div class=\"col-sm-4 col-md-4 col-lg-4\">"
                                . stars_generator(0, $reviews[$k][1])
                                . "</div>"
                                . "</div>"
                                . "<div class=\"review-item-line row\">"
                                . "<div class=\"col-lg-12\">"
                                . "<p>\"" . $reviews[$k][2] . "\"</p>"
                                . "</div>"
                                . "</div>"
                                . "</div>"
                                . "</div>"
                                . "</div>";
                    }
                }

                $html_output .= "</div>"
                        . "</div>"
                        . "</div>"
                        . "</div>"
                        . "</div>"
                        . "</div>"
                        . "</div>";
            }
            ?>

            <?php
//            echo "bought before?" . check_if_bought_before(2) . "<br>";
//            echo "bought before?" . check_if_bought_before(2) . "<br>";
//            echo "bought before?" . check_if_bought_before(3) . "<br>";
//             editreviews(2,'test editted');
//            
//            addreview(1, 'new test comment', 5)."<br>";
//            for ($i = 0; $i < sizeof(getreviews(3)); $i++) {
//                echo print_r(getreviews(3)[$i])."<br>";
//            }
            // check_if_bought_before(17);
            ?>
            <?php echo $html_output ?>

            <?php
            include "footer.inc.php";
            ?>
        </div>
    </body>
</html>