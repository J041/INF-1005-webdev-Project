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

        <div class="backend-catalogue container-fluid">

            <?php

            // Query All Products found in the 'Products' Table
            function query_all_products() {
                // Create database connection.
                $config = parse_ini_file('../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                }

                // Prepare, Bind & Execute SELECT statement to retrieve all active products
                $sql = "SELECT * FROM Products";
                $result = $conn->query($sql);

                // Defining array to store SQL output
                $results_array = [];
                $products_id_server = [];
                $products_server = [];

                // Output Query Results into results_array.
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($results_array, array($row["product_id"], $row["product_name"], $row["product_desc"], $row["product_category"], $row["quantity"], number_format($row["price"], 2, '.', ''), $row["is_active"], $row["created_at"]));
                        array_push($products_id_server, $row["product_id"]);
                        array_push($products_server, $row["product_name"]);
                    }
                }

                // Check connection
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                } else {
                    $conn->close();
                    return array($results_array, $products_id_server, $products_server);
                }
            }

            // Validates Product Name of the submit form
            function check_product_name($name, $error) {
                $sanitize_output = sanitize_regex_input($name);
                // Checks if Blank Product Name has been injected.
                if ($name == null || $name == '') {
                    array_push($error, "Please enter a Product Name.");
                } else {
                    // Ensure Product Name begins with an alphabet
                    $first_char = substr($name, 0, 1);
                    if (sanitize_regex_alpha($first_char) == "Unidentified Character") {
                        array_push($error, "Please enter a Product Name that begins with an alphabet.");
                    }

                    // Check for unspecified characters
                    if ($sanitize_output == "Unidentified Character") {
                        $output_msg = "$sanitize_output found in Product Name.";
                        array_push($error, $output_msg);
                    }
                }
                return $error;
            }

            // Validates Product Category of the submit form
            function check_product_category($category, $error) {
                $sanitize_output = sanitize_regex_input($category);
                // Checks if Blank Product Name has been injected.
                if ($category == null || $category == '') {
                    array_push($error, "Please enter a Product Category.");
                } else {
                    // Ensure Product Name begins with an alphabet
                    $first_char = substr($category, 0, 1);
                    if (sanitize_regex_alpha($first_char) == "Unidentified Character") {
                        array_push($error, "Please enter a Product Category that begins with an alphabet.");
                    }

                    // Check for unspecified characters
                    if ($sanitize_output == "Unidentified Character") {
                        $output_msg = "$sanitize_output found in Product Category.";
                        array_push($error, $output_msg);
                    }
                }
                return $error;
            }

            // Validates Product Description of the submit form
            function check_product_desc($desc, $error) {
                $sanitize_output = sanitize_regex_input($desc);
                // Checks if Blank Product Description has been injected.
                if ($desc == null || $desc == '') {
                    array_push($error, "Please enter a Product Description.");
                } else {
                    // Check for unspecified characters
                    if ($sanitize_output == "Unidentified Character") {
                        $output_msg = "$sanitize_output found in Product Description.";
                        array_push($error, $output_msg);
                    }
                }
                return $error;
            }

            // Validates Quantity of the submit form
            function check_quantity($quantity, $error) {
                // Checks if Blank Quantity has been injected.
                if ($quantity == null || $quantity == '') {
                    array_push($error, "Please enter a Quantity.");
                } else {
                    // Checks if Quantity is greater than 0.
                    if ($quantity <= 0) {
                        array_push($error, "Please enter a Quantity that is greater than 0.");
                    }

                    // Check for unspecified characters
                    $sanitize_output = sanitize_regex_int($quantity);

                    if ($sanitize_output == "Unidentified Character") {
                        $output_msg = "$sanitize_output found in Quantity.";
                        array_push($error, $output_msg);
                    }
                }
                return $error;
            }

            // Validates Price of the submit form
            function check_price($price, $error) {
                // Checks if Blank Price has been injected.
                if ($price == null || $price == '') {
                    array_push($error, "Please enter a Price.");
                } else {
                    // Checks if Price is greater than 0.
                    if ($price <= 0) {
                        array_push($error, "Please enter a Price that is greater than 0.");
                    }

                    // Check for unspecified characters
                    $sanitize_output = sanitize_regex_float($price);

                    if ($sanitize_output == "Unidentified Character") {
                        $output_msg = "$sanitize_output found in Price.";
                        array_push($error, $output_msg);
                    }
                }
                return $error;
            }

            // Validates Product Indicator of the submit form
            function check_product_indic($product_indic, $error) {
                // Checks if Blank Product Indicator has been injected.
                if ($product_indic == null || $product_indic == '') {
                    array_push($error, "Please select an indicator from the dropdown.");
                } else {
                    // Checks in other indicator values have been injected.
                    if ($product_indic < 0 || $product_indic > 1) {
                        array_push($error, "Please select an indicator from the dropdown.1");
                    }

                    // Checks for unspecified characters
                    $sanitize_output = sanitize_regex_int($product_indic);

                    if ($sanitize_output == "Unidentified Character") {
                        $output_msg = "$sanitize_output found in Product 'Active' indicator.";
                        array_push($error, $output_msg);
                    }
                }
                return $error;
            }

            // Adds Product Image into Server after passing its validation
            function check_product_img_add($product_img, $name, $error, $success, $indicator) {

                // Variables for Product Image
                $img_file_name_full = $product_img['name'];
                $img_file_name = explode('.', $product_img['name'])[0];
                $img_file_tmp = $product_img['tmp_name'];
                $img_file_ext = strtolower(end(explode('.', $product_img['name'])));

                if ($img_file_name != $name) {

                    // Storing current image file name in a temporary variable
                    $current_file_name = $img_file_name;
                    $img_file_name_full = "$name.$img_file_ext";
                    $output = "$current_file_name.$img_file_ext has been renamed to $img_file_name_full";
                }

                if ($indicator == 0) {
                    array_push($success, $output);
                    move_uploaded_file($img_file_tmp, "static/assets/img/products/" . $img_file_name_full);
                }
                return array($error, $success);
            }

            // Validation for Product Image
            function check_product_img($product_img, $name, $products_server, $error, $success) {
                // Indicates if error has occurred
                $indicator = 0;

                // Variables for Product Image
                $img_file_size = $product_img['size'];
                $img_file_ext = strtolower(end(explode('.', $product_img['name'])));

                // Accepted file extensions
                $extensions = array("jpeg", "jpg", "png");

                if (in_array($img_file_ext, $extensions) === false) {
                    array_push($error, "Unaccepted file discovered in Product Image. Please choose a JPG, JPEG or PNG file.");
                    $indicator = 1;
                }

                // Ensure Uploaded file is < 2MB
                if ($img_file_size >= 2097152) {
                    array_push($error, 'Please upload files that are less than 2MB for Product Image.');
                    $indicator = 1;
                }

                // Checks if product exist in Database
                if (in_array($name, $products_server)) {
                    array_push($error, "Duplicated products found in the database. Please consider updating/renaming the product.");
                    $indicator = 1;
                }

                $error = check_product_img_add($product_img, $name, $error, $success, $indicator)[0];
                $success = check_product_img_add($product_img, $name, $error, $success, $indicator)[1];
                return array($error, $success);
            }

            // Adds Product Category Image into Server after passing its validation
            function check_product_cat_img_add($product_cat_img, $name, $error, $success, $indicator) {

                // Variables for Product Image
                $img_file_name_full = $product_cat_img['name'];
                $img_file_name = explode('.', $product_cat_img['name'])[0];
                $img_file_tmp = $product_cat_img['tmp_name'];
                $img_file_ext = strtolower(end(explode('.', $product_cat_img['name'])));

                if ($img_file_name != $name) {

                    // Storing current image file name in a temporary variable
                    $current_file_name = $img_file_name;
                    $img_file_name_full = "$name.$img_file_ext";
                    $output = "$current_file_name.$img_file_ext has been renamed to $img_file_name_full";
                }

                if ($indicator == 0) {
                    array_push($success, $output);
                    move_uploaded_file($img_file_tmp, "static/assets/img/home/" . $img_file_name_full);
                }
                return array($error, $success);
            }

            // Validation for Product Category Image
            function check_product_cat_img($product_cat_img, $category, $name, $products_server, $error, $success) {
                // Indicates if error has occurred
                $indicator = 0;

                // Variables for Product Image
                $img_file_size = $product_cat_img['size'];
                $img_file_ext = strtolower(end(explode('.', $product_cat_img['name'])));

                // Accepted file extensions
                $extensions = array("jpeg", "jpg", "png");

                if (in_array($img_file_ext, $extensions) === false) {
                    array_push($error, "Unaccepted file discovered in Product Category Image. Please choose a JPG, JPEG or PNG file.");
                    $indicator = 1;
                }

                // Ensure Uploaded file is < 2MB
                if ($img_file_size >= 2097152) {
                    array_push($error, 'Please upload files that are less than 2MB for Product Category Image.');
                    $indicator = 1;
                }

                // Checks if product exist in Database
                if (in_array($name, $products_server)) {
                    $indicator = 1;
                }

                $error = check_product_cat_img_add($product_cat_img, $category, $error, $success, $indicator)[0];
                $success = check_product_cat_img_add($product_cat_img, $category, $error, $success, $indicator)[1];
                return array($error, $success);
            }

            // Main form validation function
            function form_validation($text_array, $float_array, $img_array, $products_server, $error, $success) {
                // Checks string fields
                $error = check_product_name($text_array[0], $error);
                $error = check_product_category($text_array[1], $error);
                $error = check_product_desc($text_array[2], $error);

                // Checks Float/Integer fields
                $error = check_quantity($float_array[0], $error);
                $error = check_price($float_array[1], $error);
                $error = check_product_indic($float_array[2], $error);

                // Check Image Fields
                if (isset($img_array[0])) {
                    $error = check_product_img($img_array[0], $text_array[0], $products_server, $error, $success)[0];
                    $success = check_product_img($img_array[0], $text_array[0], $products_server, $error, $success)[1];
                }

                if (isset($img_array[1])) {
                    $error = check_product_cat_img($img_array[1], $text_array[1], $text_array[0], $products_server, $error, $success)[0];
                    $success = check_product_cat_img($img_array[1], $text_array[1], $text_array[0], $products_server, $error, $success)[1];
                }

                return array($error, $success);
            }

            // Definining variables to store SQL Queries and Output Messages
            $results_array = query_all_products()[0];
            $products_id_server = query_all_products()[1];
            $products_server = query_all_products()[2];

            $error_msg = [];
            $success_msg = [];
            $info_msg = [];

            // Checks if user has submitted a form to add a new product
            if (isset($_POST['add_product'])) {

                // Defining Form Variables for New Product Item
                $product_id = max($products_id_server) + 1;
                $name = sanitize_input($_POST['product_name']);
                $product_img = $_FILES['product_img_file'];
                $category = sanitize_input($_POST['product_category']);
                $category_img = $_FILES['product_cat_img_file'];
                $desc = sanitize_input($_POST['product_desc']);
                $quantity = sanitize_input($_POST['quantity']);
                $price = sanitize_input($_POST['price']);
                $active = sanitize_input($_POST['is_active']);
                $created_at = '';

                // 3 arrays containing fields to be validated, 2 arrays(placeholders) for potential message(s)
                $text_array = array($name, $category, $desc);
                $float_array = array($quantity, $price, $active);
                $img_array = array($product_img, $category_img);

                // Form Validation for all input fields
                $error_msg = form_validation($text_array, $float_array, $img_array, $products_server, $error_msg, $success_msg)[0];
                $success_msg = form_validation($text_array, $float_array, $img_array, $products_server, $error_msg, $success_msg)[1];

                // Opens a SQL connection if no errors are discovered
                if (empty($error_msg)) {
                    $timezone = date_default_timezone_set('Asia/Singapore');
                    $datetime = date('Y-m-d H:i:s', time());

                    // Create database connection.
                    $config = parse_ini_file('../private/db-config.ini');
                    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

                    // Check connection
                    if ($conn->connect_error) {
                        $errorMsg = "Connection failed: " . $conn->connect_error;
                        echo $errorMsg;
                    }

                    // Prepare, Bind & Execute SELECT statement to insert new product into 'Products' Table
                    $stmt = $conn->prepare("INSERT INTO Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES (?,?,?,?,?,?,?,?)");
                    $stmt->bind_param("isssidis", $product_id, $name, $desc, $category, $quantity, $price, $active, $datetime);
                    $stmt->execute();

                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    } else {
                        $conn->close();

                        // Appends success insertion message into 'success_msg' array.
                        if (empty($error_msg)) {
                            $output_msg = "Product \"$name\" has been successfully created in the database.";
                            array_push($success_msg, $output_msg);
                            array_push($info_msg, "Page will reload in ");
                        }
                    }
                }
            }

            for ($i = 0; $i < sizeof($results_array); $i++) {
                // Defining variable to identify each update form
                $edit_product_row = "edit_product_" . $results_array[$i][0];

                // Identifies which update form is called
                if (isset($_POST[$edit_product_row])) {
                    // Defining Form Variables to update Product Item
                    $product_id = $results_array[$i][0];
                    $name = sanitize_input($_POST['product_name_edit']);
                    $product_img = $_FILES['product_img_file_edit'];
                    $category = sanitize_input($_POST['product_category_edit']);
                    $category_img = $_FILES['product_cat_img_file_edit'];
                    $desc = sanitize_input($_POST['product_desc_edit']);
                    $quantity = sanitize_input($_POST['quantity_edit']);
                    $price = sanitize_input($_POST['price_edit']);
                    $active = sanitize_input($_POST['is_active_edit']);

                    // 3 arrays containing fields to be validated, 2 arrays(placeholders) for potential message(s)
                    $text_array = array($name, $category, $desc);
                    $float_array = array($quantity, $price, $active);
                    $img_array = array($product_img, $category_img);

                    // Form Validation for all input fields
                    $error_msg = form_validation($text_array, $float_array, $img_array, $products_server, $error_msg, $success_msg)[0];
                    $success_msg = form_validation($text_array, $float_array, $img_array, $products_server, $error_msg, $success_msg)[1];

                    // Opens a SQL connection if no errors are discovered
                    if (empty($error_msg)) {
                        $timezone = date_default_timezone_set('Asia/Singapore');
                        $datetime = date('Y-m-d H:i:s', time());

                        // Create database connection.
                        $config = parse_ini_file('../private/db-config.ini');
                        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

                        // Check connection
                        if ($conn->connect_error) {
                            $errorMsg = "Connection failed: " . $conn->connect_error;
                            echo $errorMsg;
                        }

                        // Prepare, Bind & Execute UPDATE statement to update products in the 'Products' Table
                        $stmt = $conn->prepare("UPDATE Products SET product_name=?, product_desc=?, product_category=?, quantity=?, price=?, is_active=? WHERE product_id=?");
                        $stmt->bind_param("sssidii", $name, $desc, $category, $quantity, $price, $active, $product_id);
                        $status = $stmt->execute();

                        // Check connection
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        } else {
                            $conn->close();

                            // Appends success insertion message into 'success_msg' array.
                            if (empty($error_msg)) {
                                $output_msg = "Product \"$name\" has been successfully updated in the database.";
                                array_push($success_msg, $output_msg);
                                array_push($info_msg, "Page will reload in ");
                            }
                        }
                    }
                }
            }


            for ($i = 0; $i < sizeof($error_msg); $i++) {
                $html_output .= "<div class=\"row\">"
                        . "<div class=\"output-msg card\">"
                        . "<div class=\"card-body\">"
                        . "<p class=\"text-danger\">" . $error_msg[$i] . "</p>"
                        . "</div>"
                        . "</div>"
                        . "</div>";
            }

            for ($i = 0; $i < sizeof($success_msg); $i++) {
                $html_output .= "<div class=\"row\">"
                        . "<div class=\"output-msg card\">"
                        . "<div class=\"card-body\">"
                        . "<p class=\"text-success\">" . $success_msg[$i] . "</p>"
                        . "</div>"
                        . "</div>"
                        . "</div>";
            }

            for ($i = 0; $i < sizeof($info_msg); $i++) {
                $html_output .= "<div class=\"row\">"
                        . "<div class=\"output-msg card\">"
                        . "<div class=\"card-body\">"
                        . "<p class=\"text-info reload-page\">" . $info_msg[$i] . "</p>"
                        . "</div>"
                        . "</div>"
                        . "</div>";
            }

            echo $html_output;
            ?>

            <div class="backend-catalogue-header row">
                <div class="col-lg-12 col-xl-12">
                    <h1>Product Catalogue Database</h1>
                </div>
            </div>

            <div class="backend-catalogue-add-header row">
                <div class="col-md-12 col-xl-6">
                    <p>You may create, update or remove product(s) from the Product Catalogue Database table. </p>
                </div>
                <div class="col-md-12 col-xl-6">
                    <button class="btn btn-outline-primary" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-plus"></i>&nbsp; Add </button>
                    <button class="btn btn-outline-secondary d-none" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-xmark"></i>&nbsp; Close </button>
                </div>
            </div>

            <div class="backend-catalogue-add-form row d-none">
                <form action="catalogue_backend.php" method="POST" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-xl-12">
                                    <p>** Supported file formats: .jpg, .jpeg, .png</p>
                                </div>

                                <div class="col-lg-12 col-xl-6">
                                    <label class="" for="product_name">Product Name: </label>
                                    <input class="" type="text" name="product_name" placeholder="E.g. Calendar" aria-labelledby="product_name" required>
                                </div>

                                <div class="col-lg-12 col-xl-6">
                                    <label class="" for="product_img_file">Product Image: </label>
                                    <input class="" type="file" name="product_img_file" aria-labelledby="product_img_file">
                                </div>

                                <div class="col-lg-12 col-xl-6">
                                    <label class="" for="product_category">Product Category: </label>
                                    <input class="" type="text" name="product_category" list="backend_catalouge_product_cat" placeholder="E.g. Eggs and Diary Products" aria-labelledby="product_category" required>
                                    <datalist id="backend_catalouge_product_cat">
                                        <?php
                                        for ($i = 0; $i < sizeof($category_array); $i++) {
                                            echo "<option value=\"" . $category_array[$i] . "\">";
                                        }
                                        ?>
                                    </datalist>
                                </div>

                                <div class="col-lg-12 col-xl-6">
                                    <label class="" for="product_cat_img_file">Product Category Image: </label>
                                    <input class="" type="file" name="product_cat_img_file" aria-labelledby="product_cat_img_file">
                                </div>

                                <div class="col-lg-12 col-xl-6">
                                    <label class="" for="product_desc">Product Description: </label>
                                    <input class="" type="text" name="product_desc" placeholder="E.g. 2023 Calendar" aria-labelledby="product_desc" required>
                                </div>

                                <div class="col-lg-12 col-xl-6">
                                    <label class="" for="quantity">Quantity: </label>
                                    <input class="" type="number" name="quantity" placeholder="E.g. 150" aria-labelledby="quantity" required>
                                </div>

                                <div class="col-lg-12 col-xl-6">
                                    <label class="" for="price">Price: </label>
                                    <input class="" type="text" name="price" placeholder="E.g. '3.20' for $3.20" aria-labelledby="price" required>
                                </div>

                                <div class="col-lg-12 col-xl-6">
                                    <div class="backend-product-item-active-ind col-lg-12 col-xl-12">
                                        <label class="" for="product_name">Active?: </label>
                                    </div>
                                    <div class="backend-product-item-active-ind col-lg-12 col-xl-12">
                                        <select class="form-select" name="is_active" aria-label="active_product_indicator" aria-labelledby="active_product_indicator">
                                            <option selected value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="backend-catalogue-add-form-save col-md-12 col-lg-12">
                                <button class="btn btn-outline-success" tabindex="0" name="add_product" role="button" aria-pressed="false"><i class="fa-solid fa-floppy-disk"></i>&nbsp; Save </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <?php
            // Code to display all products stored in the 'Products' table.
            $html_output = "";

            $html_output .= '

                                                <div class="backend-catalogue-data row">              
                                                    <table class="table table-striped table-hover table-responsive-xl">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Name</th>
                                                                <th scope="col">Category</th>
                                                                <th scope="col">Quantity</th>
                                                                <th scope="col">Active?</th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                            ';

            // Output Products into HTML Table
            for ($i = 0; $i < sizeof($results_array); $i++) {
                // Highlight rows with product quantity <= 100
                if ($results_array[$i][4] <= 100) {
                    $html_output .= "<tr class=\"table-warning\">";
                } else {
                    $html_output .= "<tr>";
                }
                $html_output .= "<td scope=\"row\">" . $results_array[$i][0] . "</td>"
                        . "<td>" . $results_array[$i][1] . "</td>"
                        . "<td>" . $results_array[$i][3] . "</td>"
                        . "<td>" . $results_array[$i][4] . "</td>";
                if ($results_array[$i][6] == 0) {
                    $html_output .= "<td class=\"text-danger font-weight-bold\">Inctive</td>";
                } else {
                    $html_output .= "<td class=\"text-success font-weight-bold\">Active</td>";
                }
                $html_output .= "<td>"
                        . "<button type=\"button\" class=\"btn btn-outline-info btn-sm\" data-toggle=\"modal\" data-target=\"#backend_catalogue_item_" . $results_array[$i][0] . "\">Details</button>"
                        . "</td>"
                        . "</tr>";
            }

            $html_output .= '
                                                    </tbody>
                                                    </table>
                                                </div>


                            ';
// Output Products Detaisl into its respective HTML Modals
            for ($i = 0; $i < sizeof($results_array); $i++) {
                $html_output .= "<div aria-hidden=\"true\" aria-labelledby=\"backend_catalogue_item_" . $results_array[$i][0] . "\" class=\"product-item modal fade\" id=\"backend_catalogue_item_" . $results_array[$i][0] . "\" role=\"dialog\" tabindex=\"-1\">";

                $html_output .= '
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="backend-product-item-btn row">
                        <button data-dismiss="modal" type="button"><i class="fa-solid fa-xmark"></i></button>
                    </div>

                    <div class="backend-catalogue-details-header row">
                        <div class="col-md-12 col-xl-6">
                            ';

                $html_output .= "<h1>Product ID: #" . $results_array[$i][0] . "</h1>";

                $html_output .= '
                        </div>
                        <div class="col-md-12 col-xl-6">
                            <button class="btn btn-outline-primary backend-catalogue-details-edit" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-pen"></i>&nbsp; Edit </button>
                            <button class="btn btn-outline-secondary backend-catalogue-details-close d-none" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-xmark"></i>&nbsp; Close </button>
                        </div>
                    </div>

                    <div class="backend-product-details-display"> 
                        <div class="backend-product-details-display-row row">
                            <div class="col-md-12 col-xl-6">
                            ';

                $html_output .= "<h2>" . $results_array[$i][1] . "</h2>";

                $html_output .= '
                        </div>
                        <div class="col-md-12 col-xl-6">
                            ';

                $html_output .= "<h3>" . $results_array[$i][3] . "</h3>";

                $html_output .= '
                        </div>
                    </div>

                    <div class="backend-product-details-display-row row">
                        <div class="backend-product-item-img-row col-md-12 col-xl-6">
                            <div class="col-md-12 col-xl-12">
                                <h4>Product Image:</h4>
                            </div>
                            <div class="backend-product-item-img col-md-12 col-xl-12">
                            ';

                $html_output .= "<img alt=\"img_" . $results_array[$i][1] . "\" src=\"" . identify_image_type($results_array[$i][1], "static/assets/img/products/") . "\">";

                $html_output .= '
                            </div>
                        </div>
                        <div class="backend-product-item-img-row col-md-12 col-xl-6">
                            <div class="col-md-12 col-lg-12">
                                <h4>Product Category Image:</h4>
                            </div>
                            <div class="backend-product-item-img col-md-12 col-xl-12">
                            ';

                $html_output .= "<img alt=\"img_cat_" . $results_array[$i][3] . "\" src=\"" . identify_image_type($results_array[$i][3], "static/assets/img/home/") . "\">";

                $html_output .= '
                            </div>
                        </div>
                    </div>

                    <div class="backend-product-details-display-row row">
                        <div class="backend-product-item col-md-12 col-xl-4">
                            <div class="col-lg-12">
                                <h4>Product Description: </h4>
                            ';

                $html_output .= "<p>" . $results_array[$i][2] . "</p>";

                $html_output .= '
                            </div>
                        </div>
                        <div class="backend-product-item col-md-12 col-xl-4">
                            <div class="col-lg-12">
                                <h4>Quantity: </h4>
                            ';

                $html_output .= "<p>" . $results_array[$i][4] . "</p>";

                $html_output .= '
                            </div>
                        </div>
                        <div class="backend-product-item col-md-12 col-xl-4">
                            <div class="col-lg-12">
                                <h4>Price: </h4>
                            ';

                $html_output .= "<p> SGD $" . $results_array[$i][5] . "</p>";

                $html_output .= '
                            </div>
                        </div>
                        <div class="backend-product-item col-md-12 col-xl-4">
                            <div class="col-lg-12">
                                <h4>Active? </h4>
                            ';

                if ($results_array[$i][6] == 0) {
                    $html_output .= "<p>Inactive</p>";
                } else {
                    $html_output .= "<p>Active</p>";
                }

                $html_output .= '
                            </div>
                        </div>
                        <div class="backend-product-item col-md-12 col-xl-4">
                            <div class="col-lg-12">
                                <h4>Created At: </h4>
                            ';

                $html_output .= "<p>" . $results_array[$i][7] . "</p>";

                $html_output .= '
                            </div>
                        </div>
                    </div>
                </div>
                            ';

                $html_output .= '
                <div class="backend-product-details-edit d-none">
                        <form action="/catalogue_backend.php" method="POST" enctype="multipart/form-data">
                            <div class="backend-product-details-display-row row">
                                <div class="col-md-12 col-xl-6">
                                    <label class="" for="product_name_edit">Product Name: </label>
                            ';

                $html_output .= "<input class=\"\" type=\"text\" name=\"product_name_edit\" value=\"" . $results_array[$i][1] . "\" placeholder=\"E.g. Calendar \" aria-labelledby=\"product_name_edit\" required>";

                $html_output .= '
                                </div>
                                <div class="col-md-12 col-xl-6">
                                    <label class="" for="product_category_edit">Product Category: </label>
                            ';

                $html_output .= "<input class=\"\" type=\"text\" name=\"product_category_edit\" value=\"" . $results_array[$i][3] . "\" list=\"backend_catalouge_product_cat_edit\" placeholder=\"E.g. Eggs and Diary Products \" aria-labelledby=\"product_category_edit\" required>";
                $html_output .= "<datalist id=\"backend_catalouge_product_cat_edit\">";
                for ($k = 0; $k < sizeof($category_array); $k++) {
                    $html_output .= "<option value=\"" . $category_array[$k] . "\">";
                }

                $html_output .= '
                                    </datalist>
                                </div>
                            </div>

                            <div class="backend-product-details-edit-row row">
                                <div class="backend-product-item-img-row col-md-12 col-xl-6">
                                    <div class="col-md-12 col-xl-12">
                                        <label class="" for="product_img_file_edit">Product Image: </label>
                                    </div>
                                    <div class="col-md-12 col-xl-12">
                                        <input class="" type="file" name="product_img_file_edit" aria-labelledby="product_img_file_edit">
                                    </div>
                                </div>
                                <div class="backend-product-item-img-row col-md-12 col-xl-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label class="" for="product_cat_img_file_edit">Product Category Image: </label>
                                    </div>
                                    <div class="col-md-12 col-xl-12">
                                        <input class="" type="file" name="product_cat_img_file_edit" aria-labelledby="product_cat_img_file_edit">
                                    </div>
                                </div>
                            </div>

                            <div class="backend-product-details-edit-row row">
                                <div class="backend-product-item-edit col-md-12 col-xl-4">
                                    <div class="col-lg-12">
                                        <label class="" for="product_desc_edit">Product Description: </label>
                            ';

                $html_output .= "<input class=\"\" type=\"text\" name=\"product_desc_edit\" value=\"" . $results_array[$i][2] . "\" placeholder=\"E.g. 2023 Calendar\" aria-labelledby=\"product_desc_edit\" required>";

                $html_output .= '
                                    </div>
                                </div>
                                <div class="backend-product-item-edit col-md-12 col-xl-4">
                                    <div class="col-lg-12">
                                        <label class="" for="quantity_edit">Quantity: </label>
                            ';

                $html_output .= "<input class=\"\" type=\"number\" name=\"quantity_edit\" value=\"" . $results_array[$i][4] . "\" placeholder=\"E.g. 150\" aria-labelledby=\"quantity_edit\" required>";

                $html_output .= '
                                    </div>
                                </div>
                                <div class="backend-product-item-edit col-md-12 col-xl-4">
                                    <div class="col-lg-12">
                                        <label class="" for="price_edit">Price: </label>
                            ';

                $html_output .= "<input class=\"\" type=\"text\" name=\"price_edit\" value=\"" . $results_array[$i][5] . "\" placeholder=\"E.g. '3.20' for $3.20\" aria-labelledby=\"price_edit\" required>";

                $html_output .= '
                                    </div>
                                </div>
                                <div class="backend-product-item-edit col-md-12 col-xl-4">
                                    <div class="col-lg-12">
                                        <label class="" for="is_active_edit">Active? </label>
                                    </div>
                                    <div class="col-lg-12">
                                        <select class="form-select" name="is_active_edit" aria-label="active_product_indicator_edit" aria-labelledby="active_product_indicator_edit">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="backend-product-item-edit col-md-12 col-xl-4">
                                    <div class="col-lg-12">
                                        <label class="">Created At: </label>
                            ';

                $html_output .= "<p>" . $results_array[$i][7] . "</p>";

                $html_output .= '
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="backend-catalogue-edit-form-save col-md-12 col-lg-12">
                            ';

                $html_output .= "<button class=\"btn btn-outline-success\" tabindex=\"0\" name=\"edit_product_" . $results_array[$i][0] . "\" role=\"button\" aria-pressed=\"false\"><i class=\"fa-solid fa-floppy-disk\"></i>&nbsp; Update </button>";

                $html_output .= '
                                 </div>
                            </div>
                        </form>
                    </div>
                            ';

                // Modal Closing Tags
                $html_output .= '
                </div>
            </div>
        </div>
    </div>
</div>
                            ';
            }

            echo $html_output;
            ?>


        </div>

        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>