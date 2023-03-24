<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sanitize_regex_input($data) {

    // Regular Expression that only allow accepts alphanumeric, hyphen (-) & whitespace characters
    if (preg_match('/[^A-Za-z0-9- ]/', $data)) {
        return "Unidentified Character";
    } else {
        return "No Issues!";
    }
}

function sanitize_regex_desc($data) {
    
    // Regular Expression that only allow accepts alphanumeric & whitespace characters, hyphen (-), commas (-), full-stop (.) & exclamation mark (!).
    if (preg_match('/[^A-Za-z0-9-.,! ]/', $data)) {
        return "Unidentified Character";
    } else {
        return "No Issues!";
    }
}

function sanitize_regex_alpha($data) {

    // Regular Expression that only allow accepts alphabets
    if (preg_match('/[^A-Za-z]/', $data)) {
        return "Unidentified Character";
    } else {
        return "No Issues!";
    }
}

function sanitize_regex_float($data) {

    // Regular Expression that only allow accepts numeric characters and dots (.)
    if (preg_match('/[^0-9. ]/', $data)) {
        return "Unidentified Character";
    } else {
        return "No Issues!";
    }
}

function sanitize_regex_int($data) {

    // Regular Expression that only allow accepts numeric characters
    if (preg_match('/[^0-9 ]/', $data)) {
        return "Unidentified Character";
    } else {
        return "No Issues!";
    }
}

function identify_image_type($image_name, $path) {
    // Accepted file formats
    $extensions = array("jpeg", "jpg", "png");

    // Determines if Image can be found within the specified directory
    for ($i = 0; $i < 3; $i++) {
        $possible_file = "$image_name.$extensions[$i]";
        $filetopath = "$path$possible_file";

        // Returns file directory containing the correct type of image
        if (file_exists($filetopath)) {
            return $filetopath;
        }
    }

    // Returns a string if image cannot be found.
    return "Not Found";
}

function addtocart($product_id, $quantity) {
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        // Create database connection.
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

        // Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        } else {
            // Prepare the statement:
            $selectpricestmt = $conn->prepare("SELECT price FROM Products where product_id = ?");
            // Bind & execute the query statement:
            // $product_id = $product_id;
            $selectpricestmt->bind_param("i", $product_id);
            if (!$selectpricestmt->execute()) {
                $errorMsg = "Execute failed: (" . $selectpricestmt->errno . ") " . $selectpricestmt->error;
                $success = false;
            } else {
                $result = $selectpricestmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $product_price = $row["price"];
                    // echo "product_price is:" . $product_price . '<br>';
                } else {
                    $errorMsg = "less than 1 result";
                    $success = false;
                }
            }
            $selectpricestmt->close();

            // Prepare the statement:
            $orderidstmt = $conn->prepare("SELECT order_id FROM Order_History where Users_email = ? and purchased = ?");
            // Bind & execute the query statement:
            $Users_email = $_SESSION['email'];
            $purchased = 0;
            $orderidstmt->bind_param("si", $Users_email, $purchased);
            if (!$orderidstmt->execute()) {
                $errorMsg = "Execute failed: (" . $orderidstmt->errno . ") " . $orderidstmt->error;
                $success = false;
            } else {
                $result = $orderidstmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $order_id = $row["order_id"];
                    // echo "order_id is:" . $order_id . '<br>';
                } else {
                    $errorMsg = "less than 1 result";
                    $success = false;
                }
            }
            $orderidstmt->close();

            // Prepare the statement:
            $incart = false;
            $check_if_in_cart_stmt = $conn->prepare("SELECT * FROM mydb.Cart_Item where Order_History_order_id = ? and Products_product_id = ?");
            // Bind & execute the query statement:
            $check_if_in_cart_stmt->bind_param("ii", $order_id, $product_id);
            if (!$check_if_in_cart_stmt->execute()) {
                $errorMsg = "Execute failed: (" . $check_if_in_cart_stmt->errno . ") " . $check_if_in_cart_stmt->error;
                $success = false;
            } else {
                $result = $check_if_in_cart_stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $prev_quantity = $row["quantity"];
                    $incart = true;
                }
            }
            $check_if_in_cart_stmt->close();

            // Prepare the statement:
            $getitemquantitystmt = $conn->prepare("select quantity from Products where product_id=?");
            // Bind & execute the query statement:
            $getitemquantitystmt->bind_param("i", $product_id);
            if (!$getitemquantitystmt->execute()) {
                $errorMsg = "Execute failed: (" . $getitemquantitystmt->errno . ") " . $getitemquantitystmt->error;
                $success = false;
            } else {
                $result = $getitemquantitystmt->get_result();
                $row = $result->fetch_assoc();
                $backend_quantity = $row["quantity"];
                $success = true;
            }
            $getitemquantitystmt->close();

            if ($incart) {
                $quantity = $prev_quantity + $quantity;
                // echo $quantity;
                if ($quantity > $backend_quantity) {
                    $errorMsg = "Insufficient quantity";
                    $success = false;
                } else {
                    // Prepare the statement:
                    $updatecartstmt = $conn->prepare("UPDATE Cart_Item SET quantity = ? WHERE Order_History_order_id = ? and Products_product_id = ?");
                    // Bind & execute the query statement:
                    $updatecartstmt->bind_param("iii", $quantity, $order_id, $product_id);
                    if (!$updatecartstmt->execute()) {
                        $errorMsg = "Execute failed: (" . $updatecartstmt->errno . ") " . $updatecartstmt->error;
                        $success = false;
                    } else {
                        $success = true;
                    }
                    $updatecartstmt->close();
                }
            } else {

                if ($quantity > $backend_quantity) {
                    $errorMsg = "Insufficient quantity";
                    $success = false;
                } else {
                    // Prepare the statement:
                    $putincartstmt = $conn->prepare("INSERT INTO mydb.Cart_Item (Products_product_id,Order_History_order_id,quantity,price) VALUES (?,?,?,?)");
                    // Bind & execute the query statement:
                    // $quantity = 2;
                    $putincartstmt->bind_param("iiii", $product_id, $order_id, $quantity, $product_price);
                    if (!$putincartstmt->execute()) {
                        $errorMsg = "Execute failed: (" . $putincartstmt->errno . ") " . $putincartstmt->error;
                        $success = false;
                    } else {
                        $success = true;
                    }
                    $putincartstmt->close();
                }
            }
        }
        $conn->close();
    } else {
        header("Location: login.php");
    }
}

function remoevfromcart($product_id, $quantity) {
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        // Create database connection.
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

        // Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        } else {
            // Prepare the statement:
            $selectpricestmt = $conn->prepare("SELECT price FROM Products where product_id = ?");
            // Bind & execute the query statement:
            // $product_id = $product_id;
            $selectpricestmt->bind_param("i", $product_id);
            if (!$selectpricestmt->execute()) {
                $errorMsg = "Execute failed: (" . $selectpricestmt->errno . ") " . $selectpricestmt->error;
                $success = false;
            } else {
                $result = $selectpricestmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $product_price = $row["price"];
                    // echo "product_price is:" . $product_price . '<br>';
                } else {
                    $errorMsg = "less than 1 result";
                    $success = false;
                }
            }
            $selectpricestmt->close();

            // Prepare the statement:
            $orderidstmt = $conn->prepare("SELECT order_id FROM Order_History where Users_email = ? and purchased = ?");
            // Bind & execute the query statement:
            $Users_email = $_SESSION['email'];
            $purchased = 0;
            $orderidstmt->bind_param("si", $Users_email, $purchased);
            if (!$orderidstmt->execute()) {
                $errorMsg = "Execute failed: (" . $orderidstmt->errno . ") " . $orderidstmt->error;
                $success = false;
            } else {
                $result = $orderidstmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $order_id = $row["order_id"];
                    // echo "order_id is:" . $order_id . '<br>';
                } else {
                    $errorMsg = "less than 1 result";
                    $success = false;
                }
            }
            $orderidstmt->close();

            // Prepare the statement:
            $incart = false;
            $check_if_in_cart_stmt = $conn->prepare("SELECT * FROM mydb.Cart_Item where Order_History_order_id = ? and Products_product_id = ?");
            // Bind & execute the query statement:
            $check_if_in_cart_stmt->bind_param("ii", $order_id, $product_id);
            if (!$check_if_in_cart_stmt->execute()) {
                $errorMsg = "Execute failed: (" . $check_if_in_cart_stmt->errno . ") " . $check_if_in_cart_stmt->error;
                $success = false;
            } else {
                $result = $check_if_in_cart_stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $prev_quantity = $row["quantity"];
                    $incart = true;
                }
            }
            $check_if_in_cart_stmt->close();

            // Prepare the statement:
            $getitemquantitystmt = $conn->prepare("select quantity from Products where product_id=?");
            // Bind & execute the query statement:
            $getitemquantitystmt->bind_param("i", $product_id);
            if (!$getitemquantitystmt->execute()) {
                $errorMsg = "Execute failed: (" . $getitemquantitystmt->errno . ") " . $getitemquantitystmt->error;
                $success = false;
            } else {
                $result = $getitemquantitystmt->get_result();
                $row = $result->fetch_assoc();
                $backend_quantity = $row["quantity"];
                $success = true;
            }
            $getitemquantitystmt->close();

            if ($incart) {
                $quantity = $prev_quantity - $quantity;
                if ($quantity<1){
                    return ;
                }
                // echo $quantity;
                if ($quantity > $backend_quantity) {
                    $errorMsg = "Insufficient quantity";
                    $success = false;
                } else {
                    // Prepare the statement:
                    $updatecartstmt = $conn->prepare("UPDATE Cart_Item SET quantity = ? WHERE Order_History_order_id = ? and Products_product_id = ?");
                    // Bind & execute the query statement:
                    $updatecartstmt->bind_param("iii", $quantity, $order_id, $product_id);
                    if (!$updatecartstmt->execute()) {
                        $errorMsg = "Execute failed: (" . $updatecartstmt->errno . ") " . $updatecartstmt->error;
                        $success = false;
                    } else {
                        $success = true;
                    }
                    $updatecartstmt->close();
                }
            } else {

                if ($quantity > $backend_quantity) {
                    $errorMsg = "Insufficient quantity";
                    $success = false;
                } else {
                    // Prepare the statement:
                    $putincartstmt = $conn->prepare("INSERT INTO mydb.Cart_Item (Products_product_id,Order_History_order_id,quantity,price) VALUES (?,?,?,?)");
                    // Bind & execute the query statement:
                    // $quantity = 2;
                    $putincartstmt->bind_param("iiii", $product_id, $order_id, $quantity, $product_price);
                    if (!$putincartstmt->execute()) {
                        $errorMsg = "Execute failed: (" . $putincartstmt->errno . ") " . $putincartstmt->error;
                        $success = false;
                    } else {
                        $success = true;
                    }
                    $putincartstmt->close();
                }
            }
        }
        $conn->close();
    } else {
        header("Location: login.php");
    }
}

function redirect_page($url) {
    echo "<script>window.location.href='". $url ."';</script>";
    exit;
}

function logout() {
    session_unset();
    session_destroy();
    $_SESSION = array();
    // if (isset($_COOKIE[session_name()])) {
    //     setcookie(session_name(), '', time() - 3600, '/');
    //     header('Location: logout.php');
    // }
}

function UpdateUser($new_username, $old_password, $new_password) {
    // mysqli_error(MYSQLI_ERROR_OFF);
    // ini_set("display_errors", 1);
    // error_reporting(E_ALL);

    session_start();
    // Create database connection.
    $config = parse_ini_file('../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    $returnval = array();
    $iserror = false;

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $iserror = true;
    } else {
        $errorMsg = "Connection succeed";

        $updatedusername = 0;
        $updatedpassword = 3;
        $missingoldpw = false;

        $email = $_SESSION['email'];

        if (!empty($new_username)) {

            if (!preg_match("/^[a-zA-Z0-9]*$/", $new_username)) {
                $updatedusername = 2;
                $usernameErr = "Only letters, numbers and white space allowed";
            } else {
                $stmt = $conn->prepare("UPDATE Users SET username=? where email = ?");
                $sanitize_username = sanitize_input($new_username);
                $stmt->bind_param("ss", $sanitize_username, $email);
                if (!$stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $iserror = true;
                } else {
                    $result = $stmt->get_result();
                    $_SESSION['username'] = $sanitize_username;
                    $updatedusername = 1;
                }
                $stmt->close();
            }

            // $stmt = $conn->prepare("UPDATE Users SET username=? where email = ?");
            // $sanitize_username = sanitize_input($new_username);
            // $stmt->bind_param("ss", $sanitize_username, $email);
            // if (!$stmt->execute()) {
            //     $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            //     $iserror = true;
            // } else {
            //     $result = $stmt->get_result();
            //     $_SESSION['username'] = $sanitize_username;
            //     $updatedusername = 1;
            // }
            // $stmt->close();
        }


        if (!empty($new_password) && !empty($old_password)) {
            $userpwstmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
            $userpwstmt->bind_param("s", $_SESSION["email"]);
            $userpwstmt->execute();
            $result = $userpwstmt->get_result();
            $row = $result->fetch_assoc();
            $pwd_hashed = $row["password"];

            if (password_verify($old_password, $pwd_hashed)) {
                $stmt = $conn->prepare("UPDATE Users SET password=? where email = ?");
                $pwd_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt->bind_param("ss", $pwd_hashed, $email);
                // $stmt->bind_param("ss", $_POST["pwd"]);
                if (!$stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $iserror = true;
                } else {
                    $result = $stmt->get_result();
                    $updatedpassword = 1;
                }
                $stmt->close();
            } else {
                $updatedpassword = 0;
            }

            $userpwstmt->close();
        } elseif (!empty($new_password) && empty($old_password)) {
            $updatedpassword = 2;
        }
    }
    $conn->close();

    // array_push( $returnval, $errorMsg );
    // array_push( $returnval, $updatedpassword );

    if ($iserror) {
        $returnval['errorMsg'] = $errorMsg;
        $returnval['iserror'] = $iserror;
    } else {
        $returnval['updatedusername'] = $updatedusername;
        $returnval['updatedpassword'] = $updatedpassword;
        $returnval['iserror'] = $iserror;
    }

    return $returnval;

    // if ($success){
    //     if ($updatedusername && $updatedpassword){
    //         $errorMsg = "Update Successful! Username and Password has been updated";
    //     } elseif ($updatedusername && !$updatedpassword){
    //         $errorMsg = "Update successful!. Username has been updated";
    //     } elseif (!$updatedusername && $updatedpassword){
    //         $errorMsg = "Update Successful!. Password has been updated";
    //     }
    //     $returnval['errorMsg'] = $errorMsg;
    //     $returnval['updatedpassword'] = $updatedpassword;
    //     $returnval['success'] = $success;
    //     return $returnval;
    // } else {
    //     $returnval['errorMsg'] = $errorMsg;
    //     $returnval['success'] = $success;
    //     return $returnval;
    // }
}

?>