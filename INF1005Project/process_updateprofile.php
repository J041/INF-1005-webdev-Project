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
        if (!empty($_POST["username"])) {
            $stmt = $conn->prepare("UPDATE Users SET username=? where email = 'customer3@gmail.com'");
            $stmt->bind_param("s", sanitize_input($_POST["username"]));
            if (!$stmt->execute()) {
                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $success = false;
            } else {
                $result = $stmt->get_result();
                $errorMsg = "It works";
                $success = true;
            }
            $stmt->close();
        }
        
        if (!empty($_POST["pwd"])) {
            $stmt = $conn->prepare("UPDATE Users SET password=? where email = 'customer3@gmail.com'");
//            $stmt->bind_param("s", password_hash($_POST["pwd"], PASSWORD_DEFAULT));
            $stmt->bind_param("s", $_POST["pwd"]);
            if (!$stmt->execute()) {
                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $success = false;
            } else {
                $result = $stmt->get_result();
                $errorMsg = "It works";
                $success = true;
            }
            $stmt->close();
        }
//        if (isset($_FILES["profilepic"]) && !empty($_FILES["profilepic"]['name'])){
//            $errorMsg = var_dump($_FILES["profilepic"]);
//            $success = true;
//        } else {
//            $errorMsg = 'asd';
//            $success = true;
//        }
        
        if (isset($_FILES["profilepic"]) && !empty($_FILES["profilepic"]['name'])) {
            $stmt = $conn->prepare("SELECT username FROM Users where email = 'customer3@gmail.com'");
            if (!$stmt->execute()) {
                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $success = false;
            } else {
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                
                $username = $row["username"];
                $target_dir = "static/assets/img/userprofile/";
                $check_filename = $target_dir . $username.'.png';
                
                
                $filename = $_FILES['profilepic']['name'];
                $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
                $new_filename = $username . '.' . $file_extension;
                
                if (file_exists($check_filename)) {
                    // $errorMsg = "File exists";
                    copy($new_filename, $check_filename);
                    $errorMsg =  "The file " . $new_filename . " has been uploaded.";
                    $success = true;
                } else {
//                    $errorMsg = "File not exists";                            
                    if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_dir . $new_filename)) {
                        $errorMsg =  "The file " . $new_filename . " has been uploaded.";
                        $success = true;
                    } else {
                        $errorMsg =  "Sorry, there was an error uploading your file.";
                    }
                }
            }
            $stmt->close();
        }
//        } else {
//            $errorMsg = "qwe";
//        }

    }
    $conn->close();
}

UpdateUser();
if ($success){
    $output = "<h3>Update successful!</h4>" .
              "<p>" . $errorMsg . "</p>" . 
              "<a href='updateprofile.php'><button class='btn btn-success' type='submit'>Return to Home</button></a>";
} else {
    $output = "<h3>Oops!</h4>" .
              "<h4>The following errors were detected: </h4>" . 
              "<p>" . $errorMsg . "</p>" . 
              "<a href='updateprofile.php'><button class='btn btn-warning' type='submit'>Return to Login</button></a>";
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
