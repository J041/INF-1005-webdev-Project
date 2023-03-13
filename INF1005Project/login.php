<html>
    <head>
        <?php
        include "header.inc.php";
        ?>
    </head>
    <?php
    include "nav.inc.php";
    $usernameErr = $passwordErr = "";
    ?>
    <body>

        <main class="container">
            <h1>Member Login</h1>
            <p>
                existing members log in here. for new members,please go to the
                <a href="#">Sign UP PAGE</a>.
            </p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="fname">Username:</label>
                    <input class="form-control"type="text" id="username"
                           name="username" maxlength="45" placeholder="Enter username"value="<?php echo $username; ?>">
                    <span class="error"><?php if (isset($usernameErr)) echo $usernameErr; ?></span>
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input  class="form-control" type="password" id="pwd"
                            required name="pwd" name="pwd" placeholder="Enter password" value="<?php echo $pwd; ?>">
                    <span class="error"><?php if (isset($passwordErr)) echo $passwordErr; ?></span>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </main>
        <?php
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
//        if (isset($_GET['message'])) {
//            $message = $_GET['message'];
//            echo $message;
//        }
        // display form data on submission if no errors
        if (isset($_POST['submit'])) {
            $echo = "test0";
            authenticateUser();
        };

        function authenticateUser() {
// Create database connection.
            $echo = "test 01";
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'],
                    $config['password'], $config['dbname']);
            echo $config['servername'];
// Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
// Prepare the statement:
                $stmt = $conn->prepare("SELECT * FROM Users WHERE username=?");
// Bind & execute the query statement:
                $stmt->bind_param("s", $_POST["username"]);

                $stmt->execute();
                echo "test case2";
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
// Note that email field is unique, so should only have
// one row in the result set.
                    echo "test case3";
                    $row = $result->fetch_assoc();
                    $username = $row["username"];
                    $pwd_hashed = $row["password"];

                    echo $pwd_hashed . " gapspace " . $_POST["pwd"];
                    if (password_verify($_POST["pwd"], $pwd_hashed)) {
                        session_start();

// regenerate the session ID every 30 minutes
                        if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 1800) {
                            session_regenerate_id(true); // generate a new session ID
                            $_SESSION['last_activity'] = time(); // update the last activity time
                        }

// set a session timeout of 24 hours
                        session_set_cookie_params(86400);

// set a session variable
                        $_SESSION['username'] = $username;
                        header("Location: http://35.212.159.197/index.php");
                    } else {
                        echo "<h4>The following input errors were detected:</h4>";
                        echo "<p>" . $errorMsg . "</p>";
                        echo "<a href='http://35.212.159.197/register.php'><button>return to sign up</button></a>";
                    }
                } else {
                    $errorMsg = "Email not found or password doesn't match...";
                    $success = false;
                }
                $stmt->close();
            }
            $conn->close();
        }
        ?>

        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
