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
//            if (isset($_FILES['product_img_file'])) {
//                $file_name = $_FILES['product_img_file']['name'];
//                $file_size = $_FILES['product_img_file']['size'];
//                $file_tmp = $_FILES['product_img_file']['tmp_name'];
//                $file_type = $_FILES['product_img_file']['type'];
//                $file_ext = strtolower(end(explode('.', $_FILES['product_img_file']['name'])));
//
//                $extensions = array("jpeg", "jpg", "png");
//
//                array_push($error_msg, $file_name);
//
//                if (in_array($file_ext, $extensions) === false) {
//                    array_push($error_msg, "extension not allowed, please choose a JPEG or PNG file.");
//                }
//
//                if ($file_size > 2097152) {
//                    array_push($error_msg, 'File size must be excately 2 MB');
//                }
//
//                if (empty($errors) == true) {
//                    move_uploaded_file($file_tmp, "static/assets/img/products/" . $file_name);
//                    array_push($success_msg, "Success");
//                }
//            }
            // Checks if user has submitted a form to add a new product
            if (isset($_POST['add_product'])) {
                // Defining Form Variables for New Product Item
                $name = $_POST['product_name'];
                $category = $_POST['product_category'];
                $desc = $_POST['product_desc'];
                $quantity = $_POST['quantity'];
                $price = $_POST['price'];
                $active = $_POST['is_active'];
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

                    if ($sanitize_output == "Unidentified Character") {

                        if ($i == 0) {
                            $output_msg = "$sanitize_output found in Product Name.";
                        } else if ($i == 1) {
                            $output_msg = "$sanitize_output found in Product Category.";
                        } else {
                            $output_msg = "$sanitize_output found in Product Description.";
                        }

                        array_push($error_msg, $output_msg);
                    }
                }

                // Checks Float/Integer fields
                for ($i = 0; $i < 3; $i++) {
                    $sanitize_output = sanitize_regex_float($float_array[$i]);

                    if ($sanitize_output == "Unidentified Character") {

                        if ($i == 0) {
                            $output_msg = "$sanitize_output found in Quantity.";
                        } else if ($i == 1) {
                            $output_msg = "$sanitize_output found in Price.";
                        } else {
                            $output_msg = "$sanitize_output found in Product 'Active' indicator.";
                        }

                        array_push($error_msg, $output_msg);
                    }
                }

                // Checks submitted files for correct file type and duplicates
//                if (isset($_FILES['product_img_file'])) {
                    // Indicates if error has occurred
                    $indicator = 0;

                    // Variables for Product Image
                    $img_file_name_full = $_FILES['product_img_file']['name'];
                    $img_file_name = explode('.', $_FILES['product_img_file']['name']);
                    $img_file_size = $_FILES['product_img_file']['size'];
                    $img_file_tmp = $_FILES['product_img_file']['tmp_name'];
                    $img_file_ext = strtolower(end(explode('.', $_FILES['product_img_file']['name'])));
                    echo print_r($img_file_name);
                    
                    // Accepted file extensions
                    $extensions = array("jpeg", "jpg", "png");

                    if (in_array($img_file_ext, $extensions) == false) {
                        array_push($error_msg, "Unaccepted file discovered. Please choose a JPG, JPEG or PNG file.");
                        $indicator = 1;
                    }

                    if ($img_file_size >= 2097152) {
                        array_push($error_msg, 'Please upload files that are less than 2MB.');
                        $indicator = 1;
                    }
                    
                    // Array of possible files on the server
                    $img_server = array($img_file_name . '.jpg', $img_file_name . '.JPG', $img_file_name . '.png', $img_file_name . '.PNG', $img_file_name . '.jpeg', $img_file_name . '.JPEG');
                    echo print_r($img_server);
                    
                    // Checks if files with similar/duplicated naming convension can be found in the server.
                    for ($i = 0; $i < sizeof($img_server); $i++) {
                        $server_img_path = "static/assets/img/products/" . $img_server;
                        if (file_exists($server_img_path)) {
                            array_push($error_msg, "Similar/Duplicated images found in the server. Please rectify.");
                            $indicator = 1;
                        }
                    }

                    if ($indicator == 0) {
                        move_uploaded_file($img_file_tmp, "static/assets/img/products/" . $img_file_name_full);
                    }
//                }

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
                    // Prepare, Bind & Execute SELECT statement to retrieve last Product ID
                    $sql = "SELECT MAX(product_id) FROM mydb.Products";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $product_id = $row['MAX(product_id)'] + 1;
                        }
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

            <div class="backend-catalogue-add-form row">
                <form action="/catalogue_backend.php" method="POST" enctype='multipart/form-data'>
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
        array_push($results_array, array($row["product_id"], $row["product_name"], $row["product_desc"], $row["product_category"], $row["quantity"], number_format($price_string, 2, '.', ''), $row["is_active"], $row["created_at"]));
    }
}
?>

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
<?php
$html_output = "";

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

echo $html_output;
?>
                    </tbody>
                </table>
            </div>

            <div aria-hidden="true" aria-labelledby="backend_catalogue_item_11" class="product-item modal fade" id="backend_catalogue_item_11" role="dialog"tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="backend-product-item-btn row">
                                    <button data-dismiss="modal" type="button"><i class="fa-solid fa-xmark"></i></button>
                                </div>

                                <div class="backend-catalogue-details-header row">
                                    <div class="col-md-12 col-xl-6">
                                        <h1>Product ID: #1</h1>
                                    </div>
                                    <div class="col-md-12 col-xl-6">
                                        <button class="btn btn-outline-primary" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-pen"></i>&nbsp; Edit </button>
                                        <button class="btn btn-outline-secondary d-none" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-xmark"></i>&nbsp; Close </button>
                                    </div>
                                </div>

                                <div class="backend-product-details-display d-none"> 
                                    <div class="backend-product-details-display-row row">
                                        <div class="col-md-12 col-xl-6">
                                            <h2>Ferrero Rocher</h2>
                                        </div>
                                        <div class="col-md-12 col-xl-6">
                                            <h3>Sweets and Snacks</h3>
                                        </div>
                                    </div>

                                    <div class="backend-product-details-display-row row">
                                        <div class="backend-product-item-img-row col-md-12 col-xl-6">
                                            <div class="col-md-12 col-xl-12">
                                                <h4>Product Image:</h4>
                                            </div>
                                            <div class="backend-product-item-img col-md-12 col-xl-12">
                                                <img alt="img_sweets" src="static/assets/img/products/sweets.png">
                                            </div>
                                        </div>
                                        <div class="backend-product-item-img-row col-md-12 col-xl-6">
                                            <div class="col-md-12 col-lg-12">
                                                <h4>Product Category Image:</h4>
                                            </div>
                                            <div class="backend-product-item-img col-md-12 col-xl-12">
                                                <img alt="img_cat_Sweets and Snacks" src="static/assets/img/home/Sweets and Snacks.png">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="backend-product-details-display-row row">
                                        <div class="backend-product-item col-md-12 col-xl-4">
                                            <div class="col-lg-12">
                                                <h4>Product Description: </h4>
                                                <p>sweets are sweet.</p>
                                            </div>
                                        </div>
                                        <div class="backend-product-item col-md-12 col-xl-4">
                                            <div class="col-lg-12">
                                                <h4>Quantity: </h4>
                                                <p>1000</p>
                                            </div>
                                        </div>
                                        <div class="backend-product-item col-md-12 col-xl-4">
                                            <div class="col-lg-12">
                                                <h4>Price: </h4>
                                                <p>SGD $1.00</p>
                                            </div>
                                        </div>
                                        <div class="backend-product-item col-md-12 col-xl-4">
                                            <div class="col-lg-12">
                                                <h4>Active? </h4>
                                                <p>Active</p>
                                            </div>
                                        </div>
                                        <div class="backend-product-item col-md-12 col-xl-4">
                                            <div class="col-lg-12">
                                                <h4>Created At: </h4>
                                                <p>2020-11-11 09:09:09</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="backend-product-details-edit">
                                    <form action="/catalogue_backend.php" method="POST">
                                        <div class="backend-product-details-display-row row">
                                            <div class="col-md-12 col-xl-6">
                                                <label class="" for="product_name_edit">Product Name: </label>
                                                <input class="" type="text" name="product_name_edit" value="Ferrero Rocher" placeholder="Product Name: " aria-labelledby="product_name_edit" required>
                                            </div>
                                            <div class="col-md-12 col-xl-6">
                                                <label class="" for="product_category_edit">Product Category: </label>
                                                <input class="" type="text" name="product_category_edit" value="Sweets and Snacks" list="backend_catalouge_product_cat_edit" placeholder="Product Category: " aria-labelledby="product_category_edit" required>
                                                <datalist id="backend_catalouge_product_cat_edit">
<?php
for ($i = 0; $i < sizeof($category_array); $i++) {
    echo "<option value=\"" . $category_array[$i] . "\">";
}
?>
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
                                                    <input class="" type="text" name="product_desc_edit" value="sweets are sweet." placeholder="E.g. 2023 Calendar" aria-labelledby="product_desc_edit" required>
                                                </div>
                                            </div>
                                            <div class="backend-product-item-edit col-md-12 col-xl-4">
                                                <div class="col-lg-12">
                                                    <label class="" for="quantity_edit">Quantity: </label>
                                                    <input class="" type="number" name="quantity_edit" value="1000" placeholder="E.g. 150" aria-labelledby="quantity_edit" required>
                                                </div>
                                            </div>
                                            <div class="backend-product-item-edit col-md-12 col-xl-4">
                                                <div class="col-lg-12">
                                                    <label class="" for="price_edit">Price: </label>
                                                    <input class="" type="text" name="price_edit" value="1" placeholder="E.g. '3.20' for $3.20" aria-labelledby="price_edit" required>
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
                                                    <p>2020-11-11 09:09:09</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="backend-catalogue-edit-form-save col-md-12 col-lg-12">
                                                <button class="btn btn-outline-success" tabindex="0" name="edit_product" role="button" aria-pressed="false"><i class="fa-solid fa-floppy-disk"></i>&nbsp; Update </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


<?php
//            // Code to generate "More Details" Modal for each product.
//            $html_output = "";
//            
//            // Output Details of Products into HTML Modals
//            for ($i = 0; $i < sizeof($results_array); $i++) {
//                $html_output .= '
//                    <div class="product-item modal fade" id="backend_catalogue_item_1" tabindex="-1" role="dialog" aria-labelledby="backend_catalogue_item_1" aria-hidden="true">
//                        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
//                            <div class="modal-content">
//                                <div class="modal-body">
//                                    <div class="container-fluid">
//                                        <div class="product-item-btn row">
//                                            <button data-dismiss="modal" type="button"><i class="fa-solid fa-xmark"></i></button>
//                                        </div>
//                                        <div class="row">
//                                            <div class="product-item-img col-md-12 col-lg-6"><img
//                                                    alt="img_sweets" src="static/assets/img/products/sweets.png"></div>
//                                            <div class="col-md-12 col-lg-6">
//                                                <div class="product-item-row row">
//                                                    <div class="product-item-line col-lg-12"><h1>Sweets and Snacks</h1></div>
//                                                </div>
//                                                <div class="product-item-row row">
//                                                    <div class="product-item-line col-lg-12"><h2>sweets</h2></div>
//                                                </div>
//                                                <div class="product-item-row row">
//                                                    <div class="product-item-line col-lg-12"><h3>SGD $1.00</h3></div>
//                                                </div>
//                                                <div class="product-item-row row">
//                                                    <div class="product-item-line col-lg-12"><h4>1000 in stock</h4></div>
//                                                </div>
//                                                <div class="product-item-row row">
//                                                    <div class="product-item-line col-lg-12"><h5>Details: </h5></div>
//                                                </div>
//                                                <div class="product-item-row row">
//                                                    <div class="product-item-line col-lg-12"><p>sweets are sweet</p></div>
//                                                </div>
//                                                </div>
//                                            </div>
//                                        </div>
//                                    </div>
//                                </div>
//                            </div>
//                        </div>
//                    </div>
//                ';
//            }
//            
//            echo $html_output;
//            
?>

        </div>

<?php
include "footer.inc.php";
?>
    </body>
</html>