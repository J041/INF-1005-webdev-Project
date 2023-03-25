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
        ?>
        <?php
        $html_output = "";
        if (isset($_SESSION['username']) && !empty($_SESSION['username']) && $_SESSION['priority'] == 1) {
            echo $html_output;
            $html_output = '<div class="container">"
                        . "<div class="container-fluid" role="alert">"
                        . "<div class="row">"
                        . "<div class="output-msg card">"
                        . "<div class="card-body">"
                        . "<p class="text-danger">You must be logged in to access this page</p>"
                        . "</div>"
                        . "</div>"
                        . "</div>"
                        . "</div>"
                        . "</div>"';
        } else {

            echo $html_output;
        }
        ?>

        <?php
        global $email, $username, $pwd_hashed, $usernameErr, $emailErr, $passwordErr, $pwd_confirmErr;

        // define variables and set to empty values
        $usernameErr = $emailErr = $passwordErr = $pwd_confirmErr = $DupErr = null;
        $username = $email = $pwd = $pwd_confirm = "";
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (empty($_POST["username"])) {
                $usernameErr = "Name is required";
            } else {
                $name = test_input($_POST["username"]);
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z0-9]*$/", $name)) {
                    $usernameErr = "Only letters, numbers and white space allowed";
                } else {
                    if ($conn->connect_error) {
                        die("Connection failed: " . mysqli_connect_error());
                    } else {

// Prepare an SQL query to check if an email address is in the database
                        $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ? or email = ?");
                        $stmt->bind_param("ss", $_POST["username"], $_POST["email"]);
                        $stmt->execute();
// Execute the query and check if any rows were returned
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            echo "The email address exists in the database.";
                            $usernameErr = "Duplicate username or Email";
                            $success = false;
                        } else {
                            echo "The email address  does not exist in the database.";
                        }
                    }
                    $stmt->close();
                }
            }

            if (empty($_POST["email"])) {
                $emailErr = "Email is required";
            } else {
                $email = test_input($_POST["email"]);
                // check if email address is well-formed
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                }



                // Check connection
            }


            if (empty($_POST["pwd"])) {
                $passwordErr = "password is required.<br>";

                $success = false;
            } else {
                if ($_POST["pwd"] != $_POST["pwd_confirm"]) {
                    $pwd_confirmErr = "password dont match with confirm pass";
                    $success = false;
                }
            }
            // Given password
            $password = $_POST["pwd"];

//            //Validate password strength
//            $uppercase = preg_match('@[A-Z]@', $_POST["pwd"]);
//            $lowercase = preg_match('@[a-z]@',$_POST["pwd"]);
//            $number = preg_match('@[0-9]@', $_POST["pwd"]);
//            $specialChars = preg_match('@[^\w]@', $_POST["pwd"]);
//
//            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
//                $passwordErr . "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
//                $success = false;
//            }
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>
        <?php
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        // display form data on submission if no errors
        if (isset($_POST['submit'])) {
            if (empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($pwd_confirmErr)) {

                // echo "pre test";
                saveUserToDB();
                // echo "successfully registered";
                $message = "succesfully registered";
            }
        }

        function saveUserToDB() {
            global $pwd_hashed, $priority, $errorMsg, $success;
            $pwd_hashed = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
            $priority = 3;
            $success = true;
            $errorMsg = "";

            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            // echo "test1";

            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
//                    echo "test2";let
                $stmt = $conn->prepare("INSERT INTO Users (email, username,password,priority) VALUES (?,?,?,?);");
                $stmt->bind_param("ssss", $_POST["email"], $_POST["username"], $pwd_hashed, $priority);
                // echo "test3";
                // echo $priority . ($_POST["email"]);
                $stmt->execute();
                // echo "test4";
                if (!$stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                } else {
                    // echo "test5";
                    $result = $stmt->get_result();

                    $success = true;
                }
                $stmt->close();

                $stmt2 = $conn->prepare("INSERT INTO Order_History (Users_email, purchased) VALUES (?, ?)");
                $purchased = 0;
                $stmt2->bind_param("si", $_POST["email"], $purchased);
                if (!$stmt2->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt2->errno . ") " . $stmt2->error;
                    $success = false;
                } else {
                    $success = true;
                }
                $stmt2->close();

                $conn->close();
                echo '<script>window.location.href = "login.php";</script>';

                //header("Message: succesfully registered");
            }
        }

        echo'  <main class="container">
            <h1>Register as a User</h1>
            <p>
                If you are already registered,
                <a href="/login.php">Click here to Sign in !</a>.
            </p>
            <form method="post" action="register.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input class="form-control" type="text" id="username"
                           required name="username" maxlength="45" placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input  class="form-control" type="email" id="email"
                            required name="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input  class="form-control" type="password" id="pwd"
                            required name="pwd" placeholder="Enter password">
                </div>
                <div class="form-group">
                    <label for="pwd_confirm">Confirm Password:</label>
                    <input  class="form-control" type="password" id="pwd_confirm"
                            required name="pwd_confirm" placeholder="Confirm password">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" name="submit" type="submit">Submit</button>
                </div>
            </form>
        ';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($usernameErr !== null) {
                //echo '<div class="alert alert-danger" role="alert"><p>Invalid Credentials. Please try again</p></div>';
                echo '<div class="alert alert-warning" role="alert"><p>' . $usernameErr . '</p></div>';
            } elseif ($DupErr !== null) {
                echo'<div class="alert alert-warning" role="alert"><p>' . $DupErr . '</p></div>';
            } elseif ($emailErr !== null) {
                echo '<div class="alert alert-warning" role="alert"><p>' . $emailErr . '</p></div>';
            } elseif ($passwordErr !== null) {
                echo '<div class="alert alert-warning" role="alert"><p>' . $passwordErr . '</p></div>';
            } elseif ($pwd_confirmErr !== null) {
                echo '<div class="alert alert-warning" role="alert"><p>' . $pwd_confirmErr . '</p></div>';
            }
        }
        ?>
    </main>
        <?php
        include "footer.inc.php";
        ?>
</body>
</html>