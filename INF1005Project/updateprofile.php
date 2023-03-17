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
            echo $_SERVER['REQUEST_METHOD'];
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $returnval = UpdateUser($_POST["username"], $_POST["pwd"]);
                if ($returnval != False){
                    $Msg = $returnval['errorMsg'];
                    $updatedpassword = $returnval['updatedpassword'];
                    $success = True;
                } else {
                    $success = False;
                }
            }
        ?>

        <div class="container">

            <!-- <form action="process_updateprofile.php" method="post" enctype="multipart/form-data"> -->
            <form action="updateprofile.php" method="post">
                <div class="form-group">
                    <label for="email">Username:</label>
                    <input class="form-control" type="username" id="username"
                    name="username" placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input class="form-control" type="password" id="pwd"
                    name="pwd" placeholder="Enter password">
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
            </form>
            <?php
                if (isset($Msg)){
                    if ($success){
                        if ($updatedpassword){
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
                            echo    '<div class="alert alert-success" role="alert">'.
                                        '<p>'. $Msg . '</p>' . 
                                    '</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger" role="alert"><p>'. $Msg . '</p></div>';
                    }
                }
            ?> 
        </div>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
