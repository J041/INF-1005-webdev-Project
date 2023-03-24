<!DOCTYPE html>
<html lang="en">
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
        session_start();
        require 'function.php';
        logout();
    ?>
    
        <main class="container">
            <h1>You have successfully Logged out</h1>
            <p>
                <a href="login.php">click here to return to login page</a>
            </p>
        </main>
        <?php
            include "footer.inc.php";
        ?>
    </body>




</html>