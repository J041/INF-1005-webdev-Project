<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->

<?php
/*
* Helper function to authenticate the login.
*/
function authenticateUser()
{
    global $fname, $lname, $email, $pwd, $profilepic, $errorMsg, $success;
    // Create database connection.
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
    $config['password'], $config['dbname']);
    // Check connection
    if ($conn->connect_error)
    {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    }
    else
    {
        
        // my code
        
        if (!empty($_POST["email"]))
        {
            $stmt = $conn->prepare("UPDATE users SET email=? where user_id = ?");
            $stmt->bind_param("s", $_POST["email"]);
            $stmt->execute();
            $result = $stmt->get_result();
 
        }
        
        if (!empty($_POST["pwd"]))
        {
            $stmt = $conn->prepare("UPDATE users SET password=? where user_id = ?");
            $stmt->bind_param("s", $_POST["pwd"]);
            $stmt->execute();
            $result = $stmt->get_result();
        } 
                
        if (!empty($_POST["profilepic"]))
        {
            $stmt = $conn->prepare("UPDATE users SET profile_img=? where user_id = ?");
            $stmt->bind_param("s", $_POST["profilepic"]);
            $stmt->execute();
            $result = $stmt->get_result();
        } 

        
        // Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM world_of_pets_members WHERE email=?");
        // Bind & execute the query statement:
        $stmt->bind_param("s", $_POST["email"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0)
        {
            // Note that email field is unique, so should only have
            // one row in the result set.
            $row = $result->fetch_assoc();
            $fname = $row["fname"];
            $lname = $row["lname"];
            $pwd_hashed = $row["password"];
            // Check if the password matches:
//            $user_input_pwd_hashed = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
//            if (!password_verify($user_input_pwd_hashed, $pwd_hashed))
            $user_input_password = $_POST["pwd"];
            if (!password_verify($user_input_password, $pwd_hashed))
            {
                // Don't be too specific with the error message - hackers don't
                // need to know which one they got right or wrong. :)
                $errorMsg = "Email not found or password doesn't match...";
                $success = false;
            }
            else {
                $success = true;
            }
        }
        else
        {
            $errorMsg = "Email not found or password doesn't match...";
            $success = false;
        }
        $stmt->close();
    }
    $conn->close();
}

authenticateUser();

if ($success){
    $output = "<h3>Login successful!</h4>" .
              "<h4>Welcome back, " . $fname . $lname . ".</h4>" .
              "<a href='index.php'><button class='btn btn-success' type='submit'>Return to Home</button></a>";
} else {
    $output = "<h3>Oops!</h4>" .
              "<h4>The following errors were detected: </h4>" . 
              "<p>" . $errorMsg . "</p>" . 
              "<a href='login.php'><button class='btn btn-warning' type='submit'>Return to Login</button></a>";
}

?>

<html>
    <head>
        <?php
        include "header.inc.php";
        ?>
    </head>
    <body>
        <?php
        include "nav.inc.php";
        ?>
        <div class="container">
            <div class="form-group">
                <?php echo $output ?>
            </div>
        </div>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
