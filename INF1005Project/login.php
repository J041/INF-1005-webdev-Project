<html>
    <head>
        <?php
        include "header.inc.php";
        ?>
    </head>
    <?php
    include "nav.inc.php";

    ?>
    <?php
    session_start();
    global $username, $pwd, $errorMsg;
    $username = "";
    $pwd = "";
    $errorMsg = "";
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    if (isset($_GET['message'])) {
        $message = $_GET['message'];
        echo $message;
    }
    // display form data on submission if no errors

    function authenticateUser2() {

        $usernameErr = $passwordErr = "";
        $echo = "test 01";
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'],
                $config['password'], $config['dbname']);

// Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        } else {
// Prepare the statement:
            $stmt = $conn->prepare("SELECT * FROM Users WHERE username=? or email = ?");
// Bind & execute the query statement:
            $stmt->bind_param("ss", $_POST["username"], $_POST["username"]);

            $stmt->execute();
            // echo "test case2";
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $email = $row["email"];
                $username = $row["username"];
                $pwd_hashed = $row["password"];
                $priority = $row["priority"];

                if (password_verify($_POST["pwd"], $pwd_hashed)) {
                    $_SESSION['email'] = $email;
                    $_SESSION['username'] = $username;
                    $_SESSION['priority'] = $priority;
                    echo "<script>window.location.href = \"index.php\";</script>";
                    // header("Location: index.php");
                } else {
                    // $errorMsg = "Wrong password, try again";

                    $passwordErr .= "invalid password.<br>";
                    // echo"yes";
                    $success = false;
                }
            } else {
                // $errorMsg = "Email or Username is not in registered.";

                echo "invalid username or email";
 
                $success = false;
            }
            $stmt->close();

            $conn->close();
        }
    }
    ?>

    <body>

        <main class="container">
            <h1>Member Login</h1>
            <p>
                existing members log in here. <br> for new members, please go to the
                <a href="/register.php">Sign UP PAGE</a>.
            </p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="fname">Username:</label>
                    <input class="form-control"type="text" id="username"
                           required name="username" maxlength="45" placeholder="Enter username or email" value="<?php echo $username; ?>"/>
                    <span class="error"><?php if (isset($usernameErr)) echo $usernameErr; ?></span>
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input  class="form-control" type="password" id="pwd"
                            required name="pwd" name="pwd" placeholder="Enter password" value="<?php echo $pwd; ?>">
                    <span class="error"><?php if (isset($passwordErr)) echo $passwordErr; ?></span>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" name = "submit" type="submit">Submit</button>
                    <div id="login-error-msg"></div>
                </div>
            </form>
        </main>
    <?php 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        authenticateUser2();
    }
    ?>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
