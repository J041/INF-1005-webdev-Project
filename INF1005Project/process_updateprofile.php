<?php

require "function.php";

function UpdateUser()
{
    global $email, $pwd, $profilepic, $errorMsg, $success;
    // Create database connection.
    $config = parse_ini_file('../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    
    // Check connection
    if ($conn->connect_error)
    {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    }
    else
    {
        if (!empty($_POST["username"]))
        {
            $stmt = $conn->prepare("UPDATE Users SET username=? where email = 'customer3@gmail.com'");
            $stmt->bind_param("s", sanitize_input($_POST["username"]));
            if (!$stmt->execute())
            {
                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $success = false;
            } else {
                $result = $stmt->get_result();
                $errorMsg = "It works";
                $success = true;
            }
            $stmt->close();
        }
        
        if (!empty($_POST["pwd"]))
        {
            $stmt = $conn->prepare("UPDATE Users SET password=? where email = 'customer3@gmail.com'");
//            $stmt->bind_param("s", password_hash($_POST["pwd"], PASSWORD_DEFAULT));
            $stmt->bind_param("s", $_POST["pwd"]);
            if (!$stmt->execute())
            {
                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $success = false;
            } else {
                $result = $stmt->get_result();
                $errorMsg = "It works";
                $success = true;
            }
            $stmt->close();
        }
    }
    $conn->close();
}

UpdateUser();
if ($success){
    $output = "<h3>Update successful!</h4>" .
              "<p>" . $errorMsg . "</p>" . 
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
                <p>hi</p>
                <?php echo $output ?>
            </div>
        </div>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
