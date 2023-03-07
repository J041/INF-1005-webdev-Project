<header>
    <div class="container-fluid">
        <!-- Default Navigation Bar -->
        <div class="navb-brand">
            <a href="/index.php">Value Convenience Store </a>
        </div>

        <div class="navb-items d-none d-xl-flex">
            <div class="item">
                <a href="/index.php">Home</a>
            </div>
            <div class="item">
                <a href="/catalogue.php">Catalogue</a>
            </div>
            <div class="item">
                <a href="/cart.php">Cart</a>
            </div>
            <div class="item">
                <a href="/about_us.php">About Us</a>
            </div>
            <div class="item">
                <a href="/login.php">Login/Register</a>
            </div>
        </div>

        <!-- Compressed Navigation Bar Toggle -->
        <div class="mobile-navb d-lg-none">
            <a href="#" data-toggle="modal" data-target="#navbCompress">
                <i class="fa-solid fa-bars"></i>
            </a>
        </div>

        <div class="modal fade" id="navbCompress" tabindex="-1" role="dialog" aria-labelledby="navbCompress" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="/index.php">Value Convenience Store </a>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-item">
                            <a href="/index.php"><i class="fa-solid fa-house"></i> Home</a>
                        </div>
                        <div class="modal-item">
                            <a href="/catalogue.php"><i class="fa-solid fa-shop"></i> Catalogue</a>
                        </div>
                        <div class="modal-item">
                            <a href="/cart.php"><i class="fa-solid fa-cart-plus"></i> Cart</a>
                        </div>
                        <div class="modal-item">
                            <a href="/about_us.php"><i class="fa-solid fa-address-card"></i> About Us</a>
                        </div>
                        <div class="modal-item">
                            <a href="/login.php"><i class="fa-solid fa-right-to-bracket"></i> Login/Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>