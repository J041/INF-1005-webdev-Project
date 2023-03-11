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
        /*
         * Helper function to authenticate the login.
         */

        if (isset($_GET['message'])) {
            $message = $_GET['message'];
            echo $message;
        }
        authenticateUser();

        function authenticateUser() {


// Create database connection.
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
                $stmt = $conn->prepare("SELECT * FROM Users WHERE
email=?");
// Bind & execute the query statement:
                $stmt->bind_param("s", $_POST["email"]);

                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
// Note that email field is unique, so should only have
// one row in the result set.
                    $row = $result->fetch_assoc();
                    $username = $row["username"];
                    $pwd_hashed = $row["password"];

                    echo $pwd_hashed . " gapspace " . $_POST["pwd"];
                    if (password_verify($_POST["pwd"], $pwd_hashed)) {
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
                           name="username" maxlength="45" placeholder="Enter username"value="<?php echo $name; ?>">
                    <span class="error"><?php if (isset($nameErr)) echo $nameErr; ?></span>
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
        include "footer.inc.php";
        ?>
    </body>
</html>
