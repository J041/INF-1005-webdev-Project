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
            // Defining Form Variables for New Product Item
            $name = $_POST['product_name'];
            $category = $_POST['product_category'];
            $desc = $_POST['product_desc'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];
            $active = $_POST['is_active'];
            $created_at = '';

            // 2 arrays containing fields to be validated, 2 arrays(placeholders) for potential message(s)
            $text_array = array($name, $category, $desc);
            $float_array = array($quantity, $price, $active);

            $error_msg = [];
            $success_msg = [];

            // Form Validation
//            if (empty($name) && empty($category) && empty($desc) && empty($quantity) && empty($price) && empty($active)) {
//                echo "True";
//            } else {
//                if ($quantity <= 0) {
//                    array_push($error_msg, "Value must be greater than 0.");
//                } else {
//                    if ($quantity <= 0 || $price <= 0) {
//                        array_push($error_msg, "Value must be greater than 0.");
//                        if ($promo < 0 || promo >= 1) {
//                            array_push($error_msg, "Value must be between 0 to 1.");
//                        } else {
//
//                            for ($i = 0; $i < 3; $i++) {
//                                sanitize_regex_float($$text_array[$i]);
//                            }
//                        }
//                    }
//                }
//            }

            for ($i = 0; $i < 3; $i++) {
                if (sanitize_regex_float($$text_array[$i]) == "Unidentified Character"){
                    $sanitize_output = sanitize_regex_float($$text_array[$i]);
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

            echo print_r($error_msg);
//            echo "'" . $name . "''" . $category . "''" . $desc . "''" . $quantity . "''" . $price . "''" . $promo . "''" . $active . "'";

            if (!empty($error_msg)) {
                for ($i = 0; $i < sizeof($error_msg); $i++) {
                    $html_output .= "<div class=\"row\">"
                            . "<div class=\"output-msg card\">"
                            . "<div class=\"card-body\">"
                            . "<p class=\"text-danger\">" . $error_msg[$i] . "</p>"
                            . "</div>"
                            . "</div>"
                            . "</div>";
                }
            }
            ?>


            <div class="row">
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
                                <div class="col-lg-12 col-xl-4">
                                    <label class="" for="product_name">Product Name: </label>
                                    <input class="" type="text" name="product_name" placeholder="E.g. Calendar" aria-labelledby="product_name" required>
                                </div>

                                <div class="col-lg-12 col-xl-4">
                                    <label class="" for="product_category">Product Category: </label>
                                    <input class="" type="text" name="product_category" placeholder="E.g. Eggs and Diary Products" aria-labelledby="product_category" required>
                                </div>

                                <div class="col-lg-12 col-xl-4">
                                    <label class="" for="product_desc">Product Description: </label>
                                    <input class="" type="text" name="product_desc" placeholder="E.g. 2023 Calendar" aria-labelledby="product_desc" required>
                                </div>

                                <div class="col-lg-12 col-xl-4">
                                    <label class="" for="quantity">Quantity: </label>
                                    <input class="" type="number" name="quantity" placeholder="E.g. 150" aria-labelledby="quantity" required>
                                </div>

                                <div class="col-lg-12 col-xl-4">
                                    <label class="" for="price">Price: </label>
                                    <input class="" type="text" name="price" placeholder="E.g. '3.20' for $3.20" aria-labelledby="price" required>
                                </div>

                                <div class="col-lg-12 col-xl-4">
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
                                <button class="btn btn-outline-success" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-floppy-disk"></i>&nbsp; Save </button>
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