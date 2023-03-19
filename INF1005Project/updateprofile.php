<!DOCTYPE html>
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
                    // if ($returnval['success'] != False){
                    //     $Msg = $returnval['errorMsg'];
                    //     $updatedpassword = $returnval['updatedpassword'];
                    //     $success = True;
                    // } else {
                    //     $Msg = $returnval['errorMsg'];
                    //     $success = False;
                    // }
                }
            }
        ?>

        <div class="container">

            <?php
            if(isset($_SESSION['username']) && !empty($_SESSION['username'])){
                echo '<!-- <form action="process_updateprofile.php" method="post" enctype="multipart/form-data"> -->
                <form action="updateprofile.php" method="post">
                    <div class="form-group">
                        <label for="email">Username:</label>
                        <input class="form-control" type="username" id="username"
                        name="username" placeholder="Enter username">
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
                    <!-- <div class="form-group">
                        <label for="profilepic">Profile Picture</label>
                        <br>
                        <input type="file" id="profilepic" 
                            name="profilepic">
                    </div> -->
                    <div class="form-group">
                            <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </form>';
                
                // if (isset($Msg)){
                    // echo "success is:" . $success;
                    // echo "success isset is:" . isset($success);
                if (isset($success) && $success){
                    // echo "triggered";
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

                        // if ($updatedusername == 1 && $updatedpassword != 1){
                        //     $Msg = "Username has been updated";
                        //     echo    '<div class="alert alert-success" role="alert">'.
                        //                 '<p>'. $Msg . '</p>' . 
                        //             '</div>';
                        //     $Msg = "Username has been updated";
                        //     echo    '<div class="alert alert-success" role="alert">'.
                        //                 '<p>'. $Msg . '</p>' . 
                        //             '</div>';
                        // } else {
                            
                        // }
            
                        if ($updatedusername == 1 && $updatedpassword == 3){
                            $Msg = "Update successful !. Username has been updated";
                            echo    '<div class="alert alert-success" role="alert">'.
                                        '<p>'. $Msg . '</p>' . 
                                    '</div>';
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
            

                        // if ($updatedpassword != 3){
                        //     if ($updatedpassword == 1){
                        //         $Msg = "Update Successful!. Password has been updated";
                        //         echo    '<div class="alert alert-success" role="alert">'.
                        //                     '<p>'. $Msg . '</p>' . 
                        //                     '<p> <b>Please relogin</b> You will be redirected after 5 seconds </p>' . 
                        //                 '</div>';
                        //         echo '
                        //             <script>
                        //                 function logoutandlogin(){
                        //                     fetch("logout.php")
                        //                     window.location.href="login.php"
                        //                 }
    
                        //                 setTimeout(logoutandlogin, 5000);
                        //             </script>
                        //             ';
                        //     }
                
                        //     if ($updatedpassword == 0){
                        //         $Msg = "Update Failed!. Current password is Incorrect";
                        //         echo '<div class="alert alert-danger" role="alert"><p>'. $Msg . '</p></div>';
                        //     } 
                
                        //     if ($updatedpassword == 2){
                        //         $Msg = "Update Failed!. Current password is required to update password";
                        //         echo '<div class="alert alert-danger" role="alert"><p>'. $Msg . '</p></div>';
                        //     }
                        // }
                    }

                    // if ($updatedpassword == 0 || $updatedpassword == 2){
                    //     $Msg = $returnval['errorMsg'];
                    //     $success = False;
                    // } else {
                    //     $Msg = $returnval['errorMsg'];
                    //     $success = True;
                    // }



                    // if ($updatedpassword){
                    //     echo    '<div class="alert alert-success" role="alert">'.
                    //                 '<p>'. $Msg . '</p>' . 
                    //                 '<p> <b>Please relogin</b> You will be redirected after 5 seconds </p>' . 
                    //             '</div>';
                    //     echo '
                    //         <script>
                    //             function logoutandlogin(){
                    //                 fetch("logout.php")
                    //                 window.location.href="login.php"
                    //             }

                    //             setTimeout(logoutandlogin, 5000);
                    //         </script>
                    //         ';
                    // } else {
                    //     echo    '<div class="alert alert-success" role="alert">'.
                    //                 '<p>'. $Msg . '</p>' . 
                    //             '</div>';
                    // }



                }
                // } else {
                //     echo '<div class="alert alert-danger" role="alert"><p>'. $Msg . '</p></div>';
                // }
                // }
            } else {
                echo    '<div class="alert alert-danger" role="alert">'.
                            '<p>You must be logged in to access this page</p>' . 
                        '</div>';
            }
            ?> 
        </div>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
