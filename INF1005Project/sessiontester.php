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
            echo var_dump($_SESSION);
            echo "username: " . $_SESSION['username'];
            echo "priority: " . $_SESSION['priority'];
        ?>

        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
