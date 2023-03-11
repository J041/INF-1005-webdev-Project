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
        session_start();

// regenerate the session ID every 30 minutes
        if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 1800) {
            session_regenerate_id(true); // generate a new session ID
            $_SESSION['last_activity'] = time(); // update the last activity time
        }

// set a session timeout of 24 hours
        session_set_cookie_params(86400);

// set a session variable

// retrieve the session variable
        $username = $_SESSION['username'];

        ?>
        <div class="container">
            <div class="row">
                <h1>Welcome to Value Convenience Store</h1>
            </div>
        </div>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
