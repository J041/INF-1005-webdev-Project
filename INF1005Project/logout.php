<!DOCTYPE html>
<html>
    <head>
        <?php
        include "header.inc.php";
        ?>
    </head>
    <?php
    include "nav.inc.php";
    session_start();
    
    if (isset($_POST['submit'])) {
        logout();
    }
    
    function logout(){
        session_destroy();
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
            header('Location: logout.php');
        }
    }
    
    foreach ($_SESSION as $key => $value) {
        echo $key . ' => ' . $value . '<br>';
    }
    ?>
    <body>
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