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
    foreach ($_SESSION as $key => $value) {
        echo $key . ' => ' . $value . '<br>';
    }
    ?>
    <body>
        <main class="container">
            <h1>Member Registration</h1>
            <p>
                YOu have logged out
                <a href="#">Sign In page</a>.
            </p>
            <form action="process_register.php" method="post">
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input class="form-control" type="text" id="fname"
                           name="fname" maxlength="45" placeholder="Enter first name">
                </div>
                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input class="form-control" type="text" id="lname"
                           required name="lname" name="lname" maxlength="45" placeholder="Enter last name">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input class="form-control" type="email" id="email"
                           required name="email" name="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input class="form-control" type="password" id="pwd"
                           required name="pwd" name="pwd" placeholder="Enter password">
                </div>
                <div class="form-group">
                    <label for="pwd_confirm">Confirm Password:</label>
                    <input class="form-control" type="password" id="pwd_confirm"
                           required name="pwd_confirm" name="pwd_confirm" placeholder="Confirm password">
                </div>
                <div class="form-check">
                    <label>
                        <input type="checkbox" required name="agree" name="agree">
                        Agree to terms and conditions.
                    </label>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </main>
        <?php
        include "footer.inc.php";
        ?>
    </body>




</html>