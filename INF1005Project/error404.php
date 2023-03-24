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
        <div class="container">
            <div class="container-fluid" role="alert">
                <div class="row">
                    <div class="output-msg card">
                        <div class="card-body">
                            <p class="text-danger">Oops! Page Not Found.</p>
                            <p class="text-danger">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                            <form action="index.php" method="get">
                                <button class="btn btn-primary" type="submit">Go to Homepage</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
