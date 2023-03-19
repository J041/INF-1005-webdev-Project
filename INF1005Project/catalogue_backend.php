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
            // IMAGE UPLOAD NOT WORKING!
            if (isset($_FILES['product_img_file'])) {
                $errors = array();
                $file_name = $_FILES['product_img_file']['name'];
                $file_size = $_FILES['product_img_file']['size'];
                $file_tmp = $_FILES['product_img_file']['tmp_name'];
                $file_type = $_FILES['product_img_file']['type'];
                $file_ext = strtolower(end(explode('.', $_FILES['product_img_file']['name'])));

                $extensions = array("jpeg", "jpg", "png");

                array_push($error_msg, $file_name);

                if (in_array($file_ext, $extensions) === false) {
                    array_push($error_msg, "extension not allowed, please choose a JPEG or PNG file.");
                }

                if ($file_size > 2097152) {
                    array_push($error_msg, 'File size must be excately 2 MB');
                }

                if (empty($errors) == true) {
//                    move_uploaded_file($file_tmp, "images/" . $file_name);
                    array_push($success_msg, "Success");
                }
            }

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

                if (empty($error_msg)) {
                    $timezone = date_default_timezone_set('Asia/Singapore');
                    $datetime = date('Y-m-d H:i:s', time());
//                    echo "The current server timezone is: " . $timezone;

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
                    $stmt->bind_param("isssidis", $product_id, $name, $desc, $category, $quantity, $price, $is_active, $datetime);
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
                    <button class="btn btn-outline-danger d-none" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-xmark"></i>&nbsp; Close </button>
                </div>
            </div>

            <div class="backend-catalogue-add-form row">
                <form action="/catalogue_backend.php" method="POST">
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
                                    <label class="" for="product_name">Active?: </label>
                                    <select class="form-select" name="is_active" aria-label="active_product_indicator" aria-labelledby="active_product_indicator">
                                        <option selected value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
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


            <div class="backend-catalogue-data row">              
                <table class="table table-responsive-xl">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Created At</th>
                            <th>Active?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" role="alert">
                            <td>1</td>
                            <td></td>
                            <td>Item 1</td>
                            <td>Miscellaneous</td>
                            <td>Description 1</td>
                            <td>1</td>
                            <td>SGD $1.15</td>
                            <td>09 Mar 2023, 21:00:12</td>
                            <td>Active</td>

                            <!-- Close Button
                            <td>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa fa-close"></i></span>
                                </button>
                            </td>
                            -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>