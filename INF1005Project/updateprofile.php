<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        include "header.inc.php";
        ?>
    </head>
    <body>
        <main>
        <?php
            include "nav.inc.php";
        ?>
        <?php 
            require 'function.php';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if(isset($_SESSION['username']) && !empty($_SESSION['username'])){
                    if (!empty($_POST["username"]) || !empty($_POST["new_pwd"])){
                        $returnval = UpdateUser($_POST["username"], $_POST["old_pwd"], $_POST["new_pwd"]);
                        if ($returnval['iserror'] == false){
                            $updatedpassword = $returnval['updatedpassword'];
                            $updatedusername = $returnval['updatedusername'];
                            $success = true;
                        } else {
                            $Msg = $returnval['errorMsg'];
                            $success = False;
                        }
                    }
                }
            }
        ?>

        <div class="container">
            <h1>Update profile</h1>
            <?php
            if(isset($_SESSION['username']) && !empty($_SESSION['username'])){
                echo '<form action="updateprofile.php" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input class="form-control" type="text" id="username"
                        name="username" pattern="[A-Za-z0-9]+" title="only letters and numbers are allowed" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <label for="old_pwd">Current Password:</label>
                        <input class="form-control" type="password" id="old_pwd"
                        name="old_pwd" placeholder="Enter password">
                    </div>
                    <div class="form-group">
                        <label for="new_pwd">New Password:</label>
                        <input class="form-control" type="password" id="new_pwd"
                        name="new_pwd" placeholder="Enter password">
                    </div>
                    <div class="form-group">
                            <!-- <button class="btn btn-primary" type="submit">Update</button> -->
                            <button class="btn updateprofilebtn" type="submit">Update</button>
                    </div>
                </form>';
                
                if (isset($success) && $success){
                    if ($updatedusername == 1 && $updatedpassword == 1){
                        $Msg = "Update Successful! Username and Password has been updated";
                        echo    '<div class="alert alert-success" role="alert">'.
                                    '<p>'. $Msg . '</p>' . 
                                    '<p> <b>Please relogin</b> You will be redirected after 5 seconds </p>' . 
                                '</div>';
                        echo '
                                <script>
                                    function logoutandlogin(){
                                        fetch("logout.php")
                                        window.location.href="login.php"
                                    }

                                    setTimeout(logoutandlogin, 5000);
                                </script>
                            ';
                    } else {                   

                        if ($updatedusername == 1 && $updatedpassword == 3){
                            $Msg = "Update successful !. Username has been updated";
                            echo    '<div class="alert alert-success" role="alert">'.
                                        '<p>'. $Msg . '</p>' . 
                                    '</div>';
                        } elseif ($updatedusername == 2){
                            $Msg = "Update Failed !. Only letters, numbers and white space allowed for username";
                            if ($updatedpassword == 3){
                                echo '<div class="alert alert-danger" role="alert"><p>'. $Msg . '</p></div>';
                            } elseif ($updatedpassword == 1){
                                $tmpMsg = "Update Successful for Password but Failed for Username !<br>";
                                $Msg =  $tmpMsg . substr($Msg,17);
                                echo '<div class="alert alert-warning" role="alert"><p>'. $Msg . '</p></div>';
                            } else {
                                $tmpMsg = "Update Failed for username and password !<br>";
                                $Msg =  $tmpMsg . substr($Msg,17) . '<br>' ;

                                if ($updatedpassword == 0){
                                    $Msg .= "Current password is Incorrect";
                                } 
                    
                                if ($updatedpassword == 2){
                                    $Msg .= "Current password is required to update password";
                                }

                                echo '<div class="alert alert-danger" role="alert"><p>'. $Msg . '</p></div>';
                            }
                        } else {
                            if ($updatedpassword == 1){
                                $Msg = "Update Successful !. Password has been updated";
                                echo    '<div class="alert alert-success" role="alert">'.
                                            '<p>'. $Msg . '</p>' . 
                                            '<p> <b>Please relogin</b> You will be redirected after 5 seconds </p>' . 
                                        '</div>';
                                echo '
                                    <script>
                                        function logoutandlogin(){
                                            fetch("logout.php")
                                            window.location.href="login.php"
                                        }
    
                                        setTimeout(logoutandlogin, 5000);
                                    </script>
                                    ';
                            } else {
                                if ($updatedpassword == 0){
                                    $Msg = "Update Failed !. Current password is Incorrect";
                                } 
                    
                                if ($updatedpassword == 2){
                                    $Msg = "Update Failed !. Current password is required to update password";
                                }

                                if ($updatedusername == 1){
                                    $tmpMsg = "Update Successful for Username but Failed for password !<br>";
                                    $Msg =  $tmpMsg . substr($Msg,17);
                                    echo '<div class="alert alert-warning" role="alert"><p>'. $Msg . '</p></div>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert"><p>'. $Msg . '</p></div>';
                                }
                            }
                        }
                    }
                }
            } else {
                // echo    '<div class="alert alert-danger" role="alert">'.
                //             '<p>You must be logged in to access this page</p>' . 
                //         '</div>';
                $html_output = "<div class=\"container-fluid\" role=\"alert\">"
                        . "<div class=\"row\">"
                        . "<div class=\"output-msg card\">"
                        . "<div class=\"card-body\">"
                        . "<p class=\"text-danger\">You must be logged in to access this page</p>"
                        . "</div>"
                        . "</div>"
                        . "</div>"
                        . "</div>";
                echo $html_output;
            }
            ?> 
        </div>
        </main>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
