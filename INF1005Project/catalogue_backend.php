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
            // Code to add new products into Products table.
            // Checks if user has submitted a form to add a new product
            if (isset($_POST['add_product'])) {
                // Defining Form Variables for New Product Item
                $name = sanitize_input($_POST['product_name']);
                $category = sanitize_input($_POST['product_category']);
                $desc = sanitize_input($_POST['product_desc']);
                $quantity = sanitize_input($_POST['quantity']);
                $price = sanitize_input($_POST['price']);
                $active = sanitize_input($_POST['is_active']);
                $created_at = '';

                // 3 arrays containing fields to be validated, 2 arrays(placeholders) for potential message(s)
                $text_array = array($name, $category, $desc);
                $float_array = array($quantity, $price, $active);
                $img_array = array($product_img, $category_img);

                $error_msg = [];
                $success_msg = [];

                // Checks string fields
                for ($i = 0; $i < 3; $i++) {
                    $sanitize_output = sanitize_regex_input($text_array[$i]);

                    if ($i == 0) {
                        // Checks if Blank Product Name has been injected.
                        if ($text_array[$i] == null || $text_array[$i] == '') {
                            array_push($error_msg, "Please enter a Product Name.");
                        } else {
                            // Ensure Product Name begins with an alphabet
                            $first_char = substr($name, 0, 1);
                            if (sanitize_regex_alpha($first_char) == "Unidentified Character") {
                                array_push($error_msg, "Please enter a Product Name that begins with an alphabet.");
                            }

                            // Check for unspecified characters
                            if ($sanitize_output == "Unidentified Character") {
                                $output_msg = "$sanitize_output found in Product Name.";
                                array_push($error_msg, $output_msg);
                            }
                        }
                    } else if ($i == 1) {
                        // Checks if Blank Product Category has been injected.
                        if ($text_array[$i] == null || $text_array[$i] == '') {
                            array_push($error_msg, "Please enter a Product Category.");
                        } else {
                            // Ensure Product Name begins with an alphabet
                            $first_char = substr($category, 0, 1);
                            if (sanitize_regex_alpha($first_char) == "Unidentified Character") {
                                array_push($error_msg, "Please enter a Product Category that begins with an alphabet.");
                            }
                            // Check for unspecified characters
                            if ($sanitize_output == "Unidentified Character") {
                                $output_msg = "$sanitize_output found in Product Category.";
                                array_push($error_msg, $output_msg);
                            }
                        }
                    } else {
                        // Checks if Blank Product Description has been injected.
                        if ($text_array[$i] == null || $text_array[$i] == '') {
                            array_push($error_msg, "Please enter a Product Description.");
                        } else {
                            // Check for unspecified characters
                            if ($sanitize_output == "Unidentified Character") {
                                $output_msg = "$sanitize_output found in Product Description.";
                                array_push($error_msg, $output_msg);
                            }
                        }
                    }
                }

                // Checks Float/Integer fields
                for ($i = 0; $i < 3; $i++) {
                    if ($i == 0) {
                        // Checks if Blank Quantity has been injected.
                        if ($float_array[$i] == null || $float_array[$i] == '') {
                            array_push($error_msg, "Please enter a Quantity.");
                        } else {
                            // Checks if Quantity is greater than 0.
                            if ($float_array[$i] <= 0) {
                                array_push($error_msg, "Please enter a Quantity that is greater than 0.");
                            }

                            // Check for unspecified characters
                            $sanitize_output = sanitize_regex_int($float_array[$i]);

                            if ($sanitize_output == "Unidentified Character") {
                                $output_msg = "$sanitize_output found in Quantity.";
                                array_push($error_msg, $output_msg);
                            }
                        }
                    } else if ($i == 1) {
                        // Checks if Blank Quantity has been injected.
                        if ($float_array[$i] == null || $float_array[$i] == '') {
                            array_push($error_msg, "Please enter a Price.");
                        } else {
                            // Checks if Price is greater than 0.
                            if ($float_array[$i] <= 0) {
                                array_push($error_msg, "Please enter a Price that is greater than 0.");
                            }

                            // Check for unspecified characters
                            $sanitize_output = sanitize_regex_float($float_array[$i]);

                            if ($sanitize_output == "Unidentified Character") {
                                $output_msg = "$sanitize_output found in Price.";
                                array_push($error_msg, $output_msg);
                            }
                        }
                    } else {
                        // Checks if Blank Quantity has been injected.
                        if ($float_array[$i] == null || $float_array[$i] == '') {
                            array_push($error_msg, "Please select an indicator from the dropdown.");
                        } else {
                            // Checks in other indicator values have been injected.
                            if ($active < 0 || $active > 1) {
                                array_push($error_msg, "Please select an indicator from the dropdown.1");
                            }

                            // Checks for unspecified characters
                            $sanitize_output = sanitize_regex_int($float_array[$i]);

                            if ($sanitize_output == "Unidentified Character") {
                                $output_msg = "$sanitize_output found in Product 'Active' indicator.";
                                array_push($error_msg, $output_msg);
                            }
                        }
                    }
                }

                // echo var_dump($_FILES);
                // Checks submitted files for correct file type and duplicates
                if (isset($_FILES['product_img_file'])) {
                    // Indicates if error has occurred
                    $indicator = 0;

                    // Variables for Product Image
                    $img_file_name_full = $_FILES['product_img_file']['name'];
                    $img_file_name = explode('.', $_FILES['product_img_file']['name'])[0];
                    $img_file_size = $_FILES['product_img_file']['size'];
                    $img_file_tmp = $_FILES['product_img_file']['tmp_name'];
                    $img_file_ext = strtolower(end(explode('.', $_FILES['product_img_file']['name'])));
//                    echo $img_file_name_full . '<br>', $img_file_name . '<br>', $img_file_size . '<br>', $img_file_tmp . '<br>', $img_file_ext . '<br>';
                    // Accepted file extensions
                    $extensions = array("jpeg", "jpg", "png");

                    if (in_array($img_file_ext, $extensions) === false) {
                        array_push($error_msg, "Unaccepted file discovered in Product Image. Please choose a JPG, JPEG or PNG file.");
                        $indicator = 1;
                    }

                    if ($img_file_size >= 2097152) {
                        array_push($error_msg, 'Please upload files that are less than 2MB.');
                        $indicator = 1;
                    }

                    if ($img_file_name != $name) {
                        // Storing current image file name in a temporary variable
                        $current_file_name = $img_file_name;
                        $img_file_name_full = "$name.$img_file_ext";
                        $output = "$current_file_name.$img_file_ext has been renamed to $img_file_name_full";
                    }

//                    // Array of possible files on the server
//                    $img_server = array($name . '.jpg', $name . '.JPG', $name . '.png', $name . '.PNG', $name . '.jpeg', $name . '.JPEG');
//                    echo print_r($img_server);
//
//                    // Checks if files with similar/duplicated naming convension can be found in the server.
//                    for ($i = 0; $i < sizeof($img_server); $i++) {
//                        $server_img_path = "var/html/www/static/assets/img/products/" . $img_server;
//                        echo $server_img_path;
//                        echo file_exists($server_img_path);
//                        if (file_exists($server_img_path)) {
//                            array_push($error_msg, "Similar/Duplicated images found in the server. Please rectify.");
//                            $indicator = 1;
//                        }
//                    }

                    if ($indicator == 0) {
                        array_push($success_msg, $output);
                        move_uploaded_file($img_file_tmp, "static/assets/img/products/" . $img_file_name_full);
                    }
                }

                if (isset($_FILES['product_cat_img_file'])) {
                    // Indicates if error has occurred
                    $indicator = 0;

                    // Variables for Product Image
                    $img_cat_file_name_full = $_FILES['product_cat_img_file']['name'];
                    $img_cat_file_name = explode('.', $_FILES['product_cat_img_file']['name'])[0];
                    $img_cat_file_size = $_FILES['product_cat_img_file']['size'];
                    $img_cat_file_tmp = $_FILES['product_cat_img_file']['tmp_name'];
                    $img_cat_file_ext = strtolower(end(explode('.', $_FILES['product_cat_img_file']['name'])));
//                    echo $img_file_name_full . '<br>', $img_file_name . '<br>', $img_file_size . '<br>', $img_file_tmp . '<br>', $img_file_ext . '<br>';
                    // Accepted file extensions
                    $extensions = array("jpeg", "jpg", "png");

                    if (in_array($img_cat_file_ext, $extensions) === false) {
                        array_push($error_msg, "Unaccepted file discovered in Product Category Image. Please choose a JPG, JPEG or PNG file.");
                        $indicator = 1;
                    }

                    if ($img_cat_file_size >= 2097152) {
                        array_push($error_msg, 'Please upload files that are less than 2MB.');
                        $indicator = 1;
                    }

                    if ($img_cat_file_name != $category) {
                        // Storing current image file name in a temporary variable
                        $current_cat_file_name = $img_cat_file_name;
                        $img_cat_file_name_full = "$category.$img_cat_file_ext";
                        $output = "$current_cat_file_name.$img_cat_file_ext has been renamed to $img_cat_file_name_full";
                    }

//                    // Array of possible files on the server
//                    $img_server = array($name . '.jpg', $name . '.JPG', $name . '.png', $name . '.PNG', $name . '.jpeg', $name . '.JPEG');
//                    echo print_r($img_server);
//
//                    // Checks if files with similar/duplicated naming convension can be found in the server.
//                    for ($i = 0; $i < sizeof($img_server); $i++) {
//                        $server_img_path = "var/html/www/static/assets/img/products/" . $img_server;
//                        echo $server_img_path;
//                        echo file_exists($server_img_path);
//                        if (file_exists($server_img_path)) {
//                            array_push($error_msg, "Similar/Duplicated images found in the server. Please rectify.");
//                            $indicator = 1;
//                        }
//                    }

                    if ($indicator == 0) {
                        array_push($success_msg, $output);
                        move_uploaded_file($img_cat_file_tmp, "static/assets/img/home/" . $img_cat_file_name_full);
                    }
                }

                if (empty($error_msg)) {
                    $timezone = date_default_timezone_set('Asia/Singapore');
                    $datetime = date('Y-m-d H:i:s', time());

                    // Create database connection.
                    $config = parse_ini_file('../private/db-config.ini');
                    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

                    // Check connection
                    if ($conn->connect_error) {
                        $errorMsg = "Connection failed: " . $conn->connect_error;
                        $success = false;
                        echo $errorMsg;
                    }

                    // Defining 2 arrays to store all Product ID and Product Names
                    $products_id_server = [];
                    $products_server = [];

                    // Prepare, Bind & Execute SELECT statement to retrieve all Product IDs & Product Names
                    $sql = "SELECT product_id, product_name FROM mydb.Products";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            array_push($products_id_server, $row["product_id"]);
                            array_push($products_server, $row["product_name"]);
                        }
                    }

                    // Checks if product exist in Database
                    if (in_array($name, $products_server)) {
                        array_push($error_msg, "Duplicated products found in the database. Please consider updating the product.");
                    } else {
                        $product_id = max($products_id_server) + 1;

                        // Prepare, Bind & Execute SELECT statement to insert new product into 'Products' Table
                        $stmt = $conn->prepare("INSERT INTO Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES (?,?,?,?,?,?,?,?)");
                        $stmt->bind_param("isssidis", $product_id, $name, $desc, $category, $quantity, $price, $active, $datetime);
                        $stmt->execute();
                    }

                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    } else {
                        $conn->close();

                        // Appends success insertion message into 'success_msg' array.
                        if (empty($error_msg)) {
                            $output_msg = "Product \"$name\" has been successfully created in the database.";
                            array_push($success_msg, $output_msg);
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

            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
                echo $errorMsg;
            }

            // Prepare, Bind & Execute SELECT statement to retrieve all active products
            $sql = "SELECT * FROM Products";
            $result = $conn->query($sql);

            // Defining array to store SQL output
            $results_array = [];

            // Output Query Results into results_array.
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($results_array, array($row["product_id"], $row["product_name"], $row["product_desc"], $row["product_category"], $row["quantity"], number_format($row["price"], 2, '.', ''), $row["is_active"], $row["created_at"]));
                }
            }


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
                // Highlight rows with product quantity <= 30
                if ($results_array[$i][4] <= 30) {
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
                            <button class="btn btn-outline-primary" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-pen"></i>&nbsp; Edit </button>
                            <button class="btn btn-outline-secondary d-none" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-xmark"></i>&nbsp; Close </button>
                        </div>
                    </div>

                    <div class="backend-product-details-display d-none"> 
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

                $html_output .= "<img alt=\"img_cat" . $results_array[$i][3] . "\" src=\"" . identify_image_type($results_array[$i][3], "static/assets/img/home/") . "\">";

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
                <div class="backend-product-details-edit">
                        <form action="/catalogue_backend.php" method="POST" enctype="multipart/form-data">
                            <div class="backend-product-details-display-row row">
                                <div class="col-md-12 col-xl-6">
                                    <label class="" for="product_name_edit">Product Name: </label>
                            ';
                
                $html_output .= "<input class=\"\" type=\"text\" name=\"product_name_edit\" value=\"". $results_array[$i][1] ."\" placeholder=\"E.g. Calendar \" aria-labelledby=\"product_name_edit\" required>";
                
                $html_output .= '
                                </div>
                                <div class="col-md-12 col-xl-6">
                                    <label class="" for="product_category_edit">Product Category: </label>
                            ';
                
                $html_output .= "<input class=\"\" type=\"text\" name=\"product_category_edit\" value=\"". $results_array[$i][3] ."\" list=\"backend_catalouge_product_cat_edit\" placeholder=\"E.g. Eggs and Diary Products \" aria-labelledby=\"product_category_edit\" required>";
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
                
                $html_output .= "<input class=\"\" type=\"text\" name=\"product_desc_edit\" value=\"". $results_array[$i][2] ."\" placeholder=\"E.g. 2023 Calendar\" aria-labelledby=\"product_desc_edit\" required>";
                
                $html_output .= '
                                    </div>
                                </div>
                                <div class="backend-product-item-edit col-md-12 col-xl-4">
                                    <div class="col-lg-12">
                                        <label class="" for="quantity_edit">Quantity: </label>
                            ';
                
                $html_output .= "<input class=\"\" type=\"number\" name=\"quantity_edit\" value=\"". $results_array[$i][4] ."\" placeholder=\"E.g. 150\" aria-labelledby=\"quantity_edit\" required>";
                
                $html_output .= '
                                    </div>
                                </div>
                                <div class="backend-product-item-edit col-md-12 col-xl-4">
                                    <div class="col-lg-12">
                                        <label class="" for="price_edit">Price: </label>
                            ';
                
                $html_output .= "<input class=\"\" type=\"number\" name=\"price_edit\" value=\"". $results_array[$i][5] ."\" placeholder=\"E.g. '3.20' for $3.20\" aria-labelledby=\"price_edit\" required>";
                
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
                
                $html_output .= "<input class=\"\" type=\"number\" name=\"quantity_edit\" value=\"". $results_array[$i][7] ."\" placeholder=\"E.g. 150\" aria-labelledby=\"quantity_edit\" required>";
                
                $html_output .= '
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="backend-catalogue-edit-form-save col-md-12 col-lg-12">
                            ';
                
                $html_output .= "<button class=\"btn btn-outline-success\" tabindex=\"0\" name=\"edit_product_". $results_array[$i][0] ."\" role=\"button\" aria-pressed=\"false\"><i class=\"fa-solid fa-floppy-disk\"></i>&nbsp; Update </button>";
                
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