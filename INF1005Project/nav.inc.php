<header>
    <div class="container-fluid">
        <!-- Default Navigation Bar -->
        <div class="navb-brand">
            <a href="/INF1005project/index.php">Value Convenience Store </a>
        </div>

        <div class="navb-items d-none d-xl-flex">
            <form action="/INF1005project/catalogue.php" method="GET">
                <div class="search-container">
                    <input class="search-bar" id="search_bar" type="search" name="search_bar" placeholder="Search for Products..." aria-labelledby="search_bar">
                    <label class="search-bar-icon" for="search_bar"><i class="fa-solid fa-magnifying-glass"></i></label>
                </div>
            </form>
            <div class="item">
                <div class="dropdown show">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownCatalogue" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Catalogue
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCatalogue">
                        <form action="/INF1005project/catalogue.php" method="GET">
                            <div class="dropdown-item">
                                <input type="submit" name="search_bar" value="Eggs and Diary Products" />
                            </div>
                            <!-- To replace this with a loop -->
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-item">
                                <input type="submit" name="search_bar" value="Dry and Canned Goods" />
                            </div>
                            <div class="dropdown-divider"></div>
                            
                            <div class="dropdown-item">
                                <input type="submit" name="search_bar" value="Meats and Produce" />
                            </div>
                            <div class="dropdown-divider"></div>
                            
                            <div class="dropdown-item">
                                <input type="submit" name="search_bar" value="Drinks and Alcohol" />
                            </div>
                            <div class="dropdown-divider"></div>
                            
                            <div class="dropdown-item">
                                <input type="submit" name="search_bar" value="Sweets and Snacks" />
                            </div>
                            <div class="dropdown-divider"></div>
                            
                            <div class="dropdown-item">
                                <input type="submit" name="search_bar" value="Miscellaneous" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="item">
                <a href="/INF1005project/cart.php">Cart</a>
            </div>
            <div class="item">
                <a href="/INF1005project/about_us.php">About Us</a>
            </div>
            <div class="item">
                <a href="/INF1005project/login.php"><i class="fa-solid fa-right-to-bracket"></i></a>
            </div>
        </div>

        <!-- Compressed Navigation Bar Toggle -->
        <div class="mobile-navb d-xl-none">
            <a href="#" data-toggle="modal" data-target="#navbCompress">
                <i class="fa-solid fa-bars"></i>
            </a>
        </div>

        <div class="modal fade" id="navbCompress" tabindex="-1" role="dialog" aria-labelledby="navbCompress" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="/INF1005project/index.php">Value Convenience Store </a>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-item">
                            <form action="/INF1005project/catalogue.php" method="GET">
                                <input id="search_bar_modal" type="search" name="search_bar" placeholder="Search for Products..." aria-labelledby="search_bar_compressed">
                                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </form>
                        </div>
                        <div class="modal-item">
                            <a href="/INF1005project/catalogue.php"><i class="fa-solid fa-shop"></i> Catalogue</a>
                        </div>
                        <div class="modal-item">
                            <a href="/INF1005project/cart.php"><i class="fa-solid fa-cart-plus"></i> Cart</a>
                        </div>
                        <div class="modal-item">
                            <a href="/INF1005project/about_us.php"><i class="fa-solid fa-address-card"></i> About Us</a>
                        </div>
                        <div class="modal-item">
                            <a href="/INF1005project/login.php"><i class="fa-solid fa-right-to-bracket"></i> Login/Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>