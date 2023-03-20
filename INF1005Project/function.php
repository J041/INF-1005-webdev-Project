<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sanitize_regex_input($data) {
    // Strips whitespace on both sides of string, slashes and converts special characters to HTML format
    $data = sanitize_input($data);

    // Regular Expression that only allow accepts alphanumeric and whitespace characters
    if (preg_match('/[^A-Za-z0-9 ]/', $data)) {
        // echo preg_match('/[^A-Za-z0-9 ]/', $data);
        return "Unidentified Character";
    } else {
        // echo preg_match('/[^A-Za-z0-9 ]/', $data);
        return "No Issues!";
    }
    
//    if ($data == " ") {
//        $data = "";
//    } elseif (preg_match('/[^A-Za-z0-9 ]/', $data)) {
//        echo preg_match('/[^A-Za-z0-9 ]/', $data);
//        return "Unidentified Character";
//    } else {
//        echo preg_match('/[^A-Za-z0-9 ]/', $data);
//        return "No Issues!";
//    }
}

function sanitize_regex_float($data) {
    // Strips whitespace on both sides of string, slashes and converts special characters to HTML format
    $data = sanitize_input($data);

    // Regular Expression that only allow accepts numeric characters and dots (.)
    if (preg_match('/[^0-9. ]/', $data)) {
        // echo preg_match('/[^A-Za-z0-9 ]/', $data);
        return "Unidentified Character";
    } else {
        // echo preg_match('/[^A-Za-z0-9 ]/', $data);
        return "No Issues!";
    }
}



function logout(){
    session_unset();
    session_destroy();
    $_SESSION = array();
    // if (isset($_COOKIE[session_name()])) {
    //     setcookie(session_name(), '', time() - 3600, '/');
    //     header('Location: logout.php');
    // }
}

function UpdateUser($new_username, $old_password, $new_password)
{
    mysqli_error(MYSQLI_ERROR_OFF);
    ini_set("display_errors",1);
    error_reporting(E_ALL);
    
    session_start();
    // Create database connection.
    $config = parse_ini_file('../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
   
    $returnval = array();
    $iserror = false;

    // Check connection
    if ($conn->connect_error)
    {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $iserror = true;
    }
    else
    {       
        $errorMsg = "Connection succeed";

        $updatedusername = 0;
        $updatedpassword = 3;
        $missingoldpw = false;

        $email = $_SESSION['email'];

        if (!empty($new_username)) {
            
            if (!preg_match("/^[a-zA-Z0-9]*$/", $new_username)) {
                $updatedusername = 2;
                $usernameErr = "Only letters, numbers and white space allowed";
            } else {
                $stmt = $conn->prepare("UPDATE Users SET username=? where email = ?");
                $sanitize_username = sanitize_input($new_username);
                $stmt->bind_param("ss", $sanitize_username, $email);
                if (!$stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $iserror = true;
                } else {
                    $result = $stmt->get_result();
                    $_SESSION['username'] = $sanitize_username;
                    $updatedusername = 1;
                }
                $stmt->close();
            }
            
            // $stmt = $conn->prepare("UPDATE Users SET username=? where email = ?");
            // $sanitize_username = sanitize_input($new_username);
            // $stmt->bind_param("ss", $sanitize_username, $email);
            // if (!$stmt->execute()) {
            //     $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            //     $iserror = true;
            // } else {
            //     $result = $stmt->get_result();
            //     $_SESSION['username'] = $sanitize_username;
            //     $updatedusername = 1;
            // }
            // $stmt->close();
        }
        
        
        if (!empty($new_password) && !empty($old_password)) {
            $userpwstmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
            $userpwstmt->bind_param("s", $_SESSION["email"]);
            $userpwstmt->execute();
            $result = $userpwstmt->get_result();
            $row = $result->fetch_assoc();
            $pwd_hashed = $row["password"];
        
            if (password_verify($old_password, $pwd_hashed)) {
                $stmt = $conn->prepare("UPDATE Users SET password=? where email = ?");
                $pwd_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt->bind_param("ss", $pwd_hashed, $email);
                // $stmt->bind_param("ss", $_POST["pwd"]);
                if (!$stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $iserror = true;
                } else {
                    $result = $stmt->get_result();
                    $updatedpassword = 1;
                }
                $stmt->close();
            } else {
                $updatedpassword = 0;
            }
            
            $userpwstmt->close();
        } elseif (!empty($new_password) && empty($old_password)){
            $updatedpassword = 2;
        }
    }
    $conn->close();

    // array_push( $returnval, $errorMsg );
    // array_push( $returnval, $updatedpassword );

    if ($iserror){
        $returnval['errorMsg'] = $errorMsg;
        $returnval['iserror'] = $iserror;
    } else { 
        $returnval['updatedusername'] = $updatedusername;
        $returnval['updatedpassword'] = $updatedpassword;
        $returnval['iserror'] = $iserror;
    }

    return $returnval;

    // if ($success){

    //     if ($updatedusername && $updatedpassword){
    //         $errorMsg = "Update Successful! Username and Password has been updated";
    //     } elseif ($updatedusername && !$updatedpassword){
    //         $errorMsg = "Update successful!. Username has been updated";
    //     } elseif (!$updatedusername && $updatedpassword){
    //         $errorMsg = "Update Successful!. Password has been updated";
    //     }

    //     $returnval['errorMsg'] = $errorMsg;
    //     $returnval['updatedpassword'] = $updatedpassword;
    //     $returnval['success'] = $success;

    //     return $returnval;
    // } else {
    //     $returnval['errorMsg'] = $errorMsg;
    //     $returnval['success'] = $success;
    //     return $returnval;
    // }

}

?>