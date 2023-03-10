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
        <div class="container">
            <form action="process_updateprofile.php" method="post" enctype="multipart/form-data">
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
                <div class="form-group">
                    <label for="profilepic">Profile Picture</label>
                    <br>
                    <input type="file" id="profilepic" 
                           name="profilepic">
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
