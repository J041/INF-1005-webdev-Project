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
    $username = $passwordErr = $pwd = $usernameErr = "";
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    if (isset($_GET['message'])) {
        $message = $_GET['message'];
        echo $message;
    }
    // display form data on submission if no errors
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo "test1";
        authenticateUser2();
    }

    function authenticateUser2() {
        global $usernameErr , $passwordErr;
        $usernameErr = $passwordErr = "";
        echo "test 01";
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

// Check connection
        if ($conn->connect_error) {
            echo "conn error";
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        } else {
// Prepare the statement:

            $stmt = $conn->prepare("SELECT * FROM Users WHERE username=? or email = ?");
// Bind & execute the query statement:
            $stmt->bind_param("ss", $_POST["username"], $_POST["username"]);
            var_dump($_POST);
            echo $_POST["username"];

            $stmt->execute();
            // echo "test case2";
            $result = $stmt->get_result();
            var_dump($result);

            if ($result->num_rows > 0) {
                echo "result get";
                $row = $result->fetch_assoc();

                var_dump($row);
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

                    $passwordErr = "1";

                    $success = false;
                }
            } else {
                //$errorMsg = "Email or Username is not in registered.";
                $usernameErr = 1;
                //echo "invalid username or email";

                $success = false;
            }
            $stmt->close();

            $conn->close();
        }
    }

    echo'<body>

        <main class="container">
            <h1>Member Login</h1>
            <p>
                existing members log in here. <br> for new members, please go to the
                <a href="/register.php">Sign UP PAGE</a>
            </p>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input class="form-control"type="text" id="username"
                           <input class="form-control"type="text" id="username"
                           required name= "username" maxlength="45" placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input  class="form-control" type="password" id="pwd"
                            required name="pwd"  placeholder="Enter password">
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" name = "submit" type="submit">Submit</button>
                    <div id="login-error-msg"></div>
                </div>
            </form>
        </main>';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($usernameErr == 1) {
            echo '<div class="alert alert-danger" role="alert"><p> Wrong email or username entered.Please try again</p></div>';
        }
        if ($passwordErr == 1) {
            echo '<div class="alert alert-danger" role="alert"><p> Wrong password entered.Please try again</p></div>';
        }
    }
    ?>

    <?php
    include "footer.inc.php";
    ?>
</body>
</html>
