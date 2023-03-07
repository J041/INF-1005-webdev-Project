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
        <div class="container">
            <form action="process_updateprofile.php" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input class="form-control" type="email" id="email"
                    name="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input class="form-control" type="password" id="pwd"
                    name="pwd" placeholder="Enter password">
                </div>
                <div class="form-group">
                    <label for="profilepic">Profile Picture</label>
                    <br>
                    <input type="file" id="profilepic" 
                           name="filename">
                </div>
                <div class="form-group">
                        <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
