<main>
    <?php
    $email = $errorMsg = "";
    $username = $errorMsg = "";
    $success = true;
    if (empty($_POST["email"])) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else {
        $email = sanitize_input($_POST["email"]);
// Additional check to make sure e-mail address is well-formed.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.";
            $success = false;
        }
    }
    if (empty($_POST["username"])) {
        $errorMsg .= "username is required.<br>";
        $success = false;
    } else {
        $lname = sanitize_input($_POST["username"]);
// Additional check to make sure e-mail address is well-formed.
    }
    if (empty($_POST["pwd"])) {
        $errorMsg .= "password is required.<br>";

        $success = false;
    } else {
        if ($_POST["pwd"] != $_POST["pwd_confirm"]) {
            $errorMsg .= "password dont match with confirm pass";
            $success = false;
        }
    }
    // Given password
    $password = $_POST["pwd"];

// Validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $errorMsg . "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
        $success = false;
    }
    if ($success) {
        echo "<h4>Registration successful!</h4>";
        echo "<p>Welcome " . $username;
        echo "<br> <a href='http://35.212.197.65/lab08/login.php'><button>return to sign up</button></a>";
        saveMemberToDB();
    } else {

        echo "<h4>The following input errors were detected:</h4>";
        echo "<p>" . $errorMsg . "</p>";
        echo "<a href='http://localhost:8000/register.php'><button>return to sign up</button></a>";
    }

//Helper function that checks input for malicious or unwanted content.
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /*
     * Helper function to write the member data to the DB
     */

    function saveMemberToDB() {
        $pwd_hashed = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
        $success = true;
        $errorMsg = "";

        global $product_id, $product_name, $product_desc, $product_category, $quantity, $price, $is_active, $created_at, $promo;
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
        $stmt = $conn->prepare("INSERT INTO mydb.Users (email, username,
password,profile_img,priority) VALUES (?, ?, ?, ?,?)");

        // Bind & execute the query statement:
        $is_active = 1;
        $stmt->bind_param($_POST["email"], $_POST["username"], $pwd_hashed, " ", " ");
//                echo "<p>" . $stmt . "</p>";


        $stmt->execute();
        $result = $stmt->get_result();
//                echo "<p>" . $result . "</p>";
//                $row = $result->fetch_assoc();
//                echo "<p>" . $row["product_name"] . "</p>";
//                echo "Connection still successfully";
//        if ($result->num_rows > 0) {
////                    $row = $result->fetch_assoc();
////                    echo "<p>" . $row["product_name"] . "</p>";
//            while ($row = $result->fetch_assoc()) {
//                echo "<p>" . $row["product_name"] . "</p>";
//                echo "<p>" . $row["price"] . "</p>";
//            }
    }

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        $conn->close();
    }
    ?>
    <?php
    include "footer.inc.php";
    ?>
</main>
