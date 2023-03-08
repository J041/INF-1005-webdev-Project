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
        /*
          $servername = "localhost";
          $username = "username";
          $password = "password";

          // Create connection
          $conn = mysqli_connect($servername, $username, $password);

          // Check connection
          if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
          }
          echo "Connected successfully";
         */
        ?>
        
        <div class="container">
            <!-- 
            List of Product Categories:
            - Eggs and Diary Products
            - Dry and Canned Goods
            - Meats and Produce
            - Drinks and Alcohol
            - Sweets and Snacks
            - Miscellaneous
            -->
            <div class="row">
                <div class="container catalogue-display">
                    <h1>Home/Products/Eggs and Dairy Products</h1>
                    <h2>Eggs and Dairy Products</h2>
                </div>
            </div>
            
            <div class="row">
                <div class="catalogue-box col-sm-12 col-md-6 col-lg-4">              
                    <div class="catalogue-items">
                        <img src="static/assets/img/logo.png" alt="img">
                    </div>
                    <div class="catalogue-items">
                        <p>Item 1</p>
                    </div>
                    <div class="catalogue-items">
                        <p>SGD $1.00</p>
                    </div>
                    <div class="catalogue-button">
                        <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#catalogue_item">
                            More Details
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm">
                            + Add to Cart <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                </div>
                
                <!<!-- Testing - To be removed -->
                <div class="catalogue-box col-sm-12 col-md-6 col-lg-4">              
                    <div class="catalogue-items">
                        <img src="static/assets/img/logo.png" alt="img">
                    </div>
                    <div class="catalogue-items">
                        <p>Item 2</p>
                    </div>
                    <div class="catalogue-items">
                        <p>SGD $2.00</p>
                    </div>
                    <div class="catalogue-button">
                        <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#catalogue_item">
                            More Details
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm">
                            + Add to Cart <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                </div>
                
                <div class="catalogue-box col-sm-12 col-md-6 col-lg-4">              
                    <div class="catalogue-items">
                        <img src="static/assets/img/logo.png" alt="img">
                    </div>
                    <div class="catalogue-items">
                        <p>Item 3</p>
                    </div>
                    <div class="catalogue-items">
                        <p>SGD $3.00</p>
                    </div>
                    <div class="catalogue-button">
                        <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#catalogue_item">
                            More Details
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm">
                            + Add to Cart <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                </div>
                
                <div class="catalogue-box col-sm-12 col-md-6 col-lg-4">              
                    <div class="catalogue-items">
                        <img src="static/assets/img/logo.png" alt="img">
                    </div>
                    <div class="catalogue-items">
                        <p>Item 4</p>
                    </div>
                    <div class="catalogue-items">
                        <p>SGD $4.00</p>
                    </div>
                    <div class="catalogue-button">
                        <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#catalogue_item">
                            More Details
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm">
                            + Add to Cart <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                </div>
            </div>



            <!-- Modal -->
            <div class="modal fade" id="catalogue_item" tabindex="-1" role="dialog" aria-labelledby="catalogue_item" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            Item Content
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