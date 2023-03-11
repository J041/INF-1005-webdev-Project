<!DOCTYPE html>
<html>
    <head>
        <?php
        include "header.inc.php";
        ?>
    </head>
    <?php
    include "nav.inc.php";
    ?>
    <body>
        <?php
        // define variables and set to empty values
        $nameErr = $emailErr = $passwordErr = $pwd_confirmErr = "";
        $username = $email = $gender = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["username"])) {
                $usernameErr = "Name is required";
            } else {
                $name = test_input($_POST["username"]);
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                    $usernameErr = "Only letters and white space allowed";
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
            }

            if (empty($_POST["pwd"])) {
                $passwordErr .= "password is required.<br>";

                $success = false;
            } else {
                if ($_POST["pwd"] != $_POST["pwd_confirm"]) {
                    $pwd_confirmErr .= "password dont match with confirm pass";
                    $success = false;
                }
            }
            // Given password
            $password = $_POST["pwd"];

// Validate password strength
//            $uppercase = preg_match('@[A-Z]@', $password);
//            $lowercase = preg_match('@[a-z]@', $password);
//            $number = preg_match('@[0-9]@', $password);
//            $specialChars = preg_match('@[^\w]@', $password);
//
//            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
//                $errorMsg . "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
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
        <main class="container">
            <h1>Register as a User</h1>
            <p>
                If you are already registered,
                <a href="#">Click here </a>to Sign in!!!.
            </p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="fname">Username:</label>
                    <input class="form-control"type="text" id="username"
                           name="username" maxlength="45" placeholder="Enter username"value="<?php echo $name; ?>">
                    <span class="error"><?php if (isset($nameErr)) echo $nameErr; ?></span>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input  class="form-control" type="email" id="email"
                            required name="email" name="email" placeholder="Enter email" value="<?php echo $email; ?>">
                    <span class="error"><?php if (isset($emailErr)) echo $emailErr; ?></span>
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input  class="form-control" type="password" id="pwd"
                            required name="pwd" name="pwd" placeholder="Enter password" value="<?php echo $pwd; ?>">
                    <span class="error"><?php if (isset($passwordErr)) echo $passwordErr; ?></span>
                </div>
                <div class="form-group">
                    <label for="pwd_confirm">Confirm Password:</label>
                    <input  class="form-control" type="password" id="pwd_confirm"
                            required name="pwd_confirm" name="pwd_confirm" placeholder="Confirm password" value="<?php echo $pwd_confirm; ?>">
                    <span class="error"><?php if (isset($pwd_confirmErr)) echo $pwd_confirmErr; ?></span>
                </div>
                <input type="submit" name="submit" value="Submit">
            </form>

            <?php
            // display form data on submission if no errors
            if (isset($_POST['submit'])) {
                if (empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($pwd_confirmErr)) {
                    echo "pre test";
                    saveUserToDB();
                    echo "successfully registered";
                    $message = "succesfully registered";
                    header("Location: https://35.212.159.197/login.php?message=" . urlencode($message));
                }
            }

            function saveUserToDB() {
                $pwd_hashed = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
                $success = true;
                $errorMsg = "";

                global $email, $username, $pwd_hashed;
                $is_active = 1;
                // Create database connection.
                $config = parse_ini_file('../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                }
//                echo "Connected successfully";
                // Prepare query statement:
                $stmt = $conn->prepare("INSERT INTO Users (email, username,
password,profile_img,priority) VALUES (?, ?, ?, ?,?)");

                // Bind & execute the query statement:
                $is_active = 1;
                $stmt->bind_param("s", $_POST["email"], $_POST["username"], $pwd_hashed, " ", " ");
//                echo "<p>" . $stmt . "</p>";


                $stmt->execute();
                $result = $stmt->get_result();
            }

            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            } else {
                $conn->close();
            }
    
            
            ?>
        </main>
<?php
include "footer.inc.php";
?>
    </body>
</html>