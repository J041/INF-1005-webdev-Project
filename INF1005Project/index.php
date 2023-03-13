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
        <main class="home">
            <div class="container header">
                <img src="static\assets\img\home\banner.png" alt="PROMOTIONS" class="banner"/>
                <h1 class="display-4">PROMOTIONS</h1>
            </div>


            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                </ol>

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="static\assets\img\home\tortilla_new_arrival.jpg" class="d-block w-100" alt="Wild Landscape" style="width:auto; max-height:50%;"/>
                    </div>
                    <div class="carousel-item">
                        <img src="static\assets\img\home\pepsi_promo.png" class="d-block w-100" alt="Camera"/>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </a>
            </div>

            <div class="container header" >
                <img src="static\assets\img\home\banner.png" alt="CATEGORIES" class="banner"/>
                <h1 class="display-4">CATEGORIES</h1>
            </div>

            <div class="container categories">    
                <div class="row">
                    <div class="col-sm-4 categories" >
                        <div class="thumbnail d-flex align-items-center justify-content-center">
                            <a href="#">
                                <img src="static\assets\img\home\snacks_icon.png" alt="Snacks" class="category-icon">
                                <p class="text-center">Snacks</p>
                            </a>    
                        </div>
                    </div>
                    <div class="col-sm-4 categories">
                        <div class="thumbnail d-flex align-items-center justify-content-center">
                            <a href="#">
                                <img src="static\assets\img\home\drinks_icon.png" alt="Drinks" class="category-icon">
                                <p class="text-center">Drinks</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-4 categories">
                        <div class="thumbnail d-flex align-items-center justify-content-center">
                            <a href="#">
                                <img src="static\assets\img\home\toys_icon.png" alt="Toys" class="category-icon">
                                <p class="text-center">Toys</p>
                            </a>
                        </div>
                    </div>               
                </div>
                <div class="row">
                    <div class="col-sm-4 categories">
                        <div class="thumbnail d-flex align-items-center justify-content-center">
                            <a href="#">
                                <img src="static\assets\img\home\accessories_icon.png" alt="Accessories" class="category-icon">
                                <p class="text-center">Accessories</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-4 categories">    
                        <div class="thumbnail d-flex align-items-center justify-content-center">
                            <a href="#">
                                <img src="static\assets\img\home\household_icon.png" alt="Household Items" class="category-icon">
                                <p class="text-center">Household Items</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-4 categories">
                        <div class="thumbnail d-flex align-items-center justify-content-center">
                            <a href="#">
                                <img src="static\assets\img\home\periodical_icon.jpg" alt="Reading Materials" class="category-icon">
                                <p class="text-center">Reading Materials</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>   

            <div class="container header" >
                <img src="static\assets\img\home\banner.png" alt="DAILY DEALS" class="banner"/>
                <h1 class="display-4">DAILY DEALS</h1>
            </div>

            <div class="container-fluid cards-row">
                <div class="container">
                    <div class="row">

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname1">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname2">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname3">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname4">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname5">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <div class="container header" >
                <img src="static\assets\img\home\banner.png" alt="TRENDING ITEMS" class="banner"/>
                <h1 class="display-4">TRENDING ITEMS</h1>
            </div>

            <div class="container-fluid cards-row">
                <div class="container">
                    <div class="row">
                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname6">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname7">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname8">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname9">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname10">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="container-fluid cards-row">
                <div class="container">
                    <div class="row">
                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname11">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname12">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname13">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname14">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg productbox">
                            <div class="thumbnail productitem">
                                <img src="static\assets\img\home\example.jpg" alt="productname15">
                                <div class="caption">
                                    <h3>&lt;Product Name&gt;</h3>
                                    <p class="card-description">&lt;Product Description&gt;</p>
                                    <div class="flex-box cartbutton">
                                        <input type="image" name="submit"
                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"
                                               alt="Add to Cart">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
   
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>
