<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
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
        <main class="container">
            <h1>Member Login</h1>
            <p>
                existing members log in here. for new members,please go to the
                <a href="#">Sign UP PAGE</a>.
            </p>
            <form action="process_login.php" method="post">

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
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </main>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>