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

        <div class="backend-catalogue container-fluid">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="backend-catalogue-display-row">
                    <h1>Product Catalogue Database</h1>
                </div>
                <div class="backend-catalogue-display-row">
                    <div>
                    <p>You may create, update or remove product(s) from the Product Catalogue Database table. </p>
                    </div>
                    <div>
                    <button class="btn btn-outline-primary" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-plus"></i>&nbsp; Add </button>
                    </div>
                    <div>
                    <button class="btn btn-outline-danger d-none" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-xmark"></i>&nbsp; Close </button>
                    </div>
                </div>
                <form action="/catalogue_backend" method="POST">
                    <div class="card">
                        <div class="card-body">
                            <div class="backend-catalogue-display-row col-sm-12 col-md-12 col-lg-12 row">
                                <div class="backend-catalogue-display-item col-sm-12 col-lg-4">
                                    <label class="" for="product_name">Product Name: </label>
                                    <input class="" type="text" name="product_name" placeholder="E.g. Calendar" aria-labelledby="product_name">
                                </div>

                                <div class="backend-catalogue-display-item col-sm-12 col-lg-4">
                                    <label class="" for="product_category">Product Category: </label>
                                    <input class="" type="text" name="product_category" placeholder="E.g. Eggs and Diary Products" aria-labelledby="product_category">
                                </div>

                                <div class="backend-catalogue-display-item col-sm-12 col-lg-4">
                                    <label class="" for="product_desc">Product Description: </label>
                                    <input class="" type="text" name="product_desc" placeholder="E.g. 2023 Calendar" aria-labelledby="product_desc">
                                </div>
                            </div>

                            <div class="backend-catalogue-display-row col-sm-12 col-md-12 col-xl-12 row">
                                <div class="backend-catalogue-display-item col-sm-12 col-lg-4">
                                    <label class="" for="quantity">Quantity: </label>
                                    <input class="" type="number" name="quantity" placeholder="E.g. 150" aria-labelledby="quantity">
                                </div>

                                <div class="backend-catalogue-display-item col-sm-12 col-lg-4">
                                    <label class="" for="price">Price: </label>
                                    <input class="" type="text" name="price" placeholder="E.g. '3.20' for $3.20" aria-labelledby="price">
                                </div>

                                <div class="backend-catalogue-display-item col-sm-12 col-lg-4">
                                    <label class="" for="promotion">Promotion: </label>
                                    <input class="" type="text" name="promotion" placeholder="E.g. '0.2' for 20% of Item Price" aria-labelledby="promotion">
                                </div>
                            </div>

                            <div class="backend-catalogue-display-row col-sm-12 col-md-12 col-xl-12 row">
                                <div class="backend-catalogue-display-item col-sm-12 col-lg-4">
                                    <label class="" for="product_name">Active?: </label>
                                    <select class="" name="is_active" aria-labelledby="product_name">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="backend-catalogue-display-row">
                            <button class="btn btn-outline-success" tabindex="0" role="button" aria-pressed="false"><i class="fa-solid fa-floppy-disk"></i>&nbsp; Save </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="backend-catalogue-table col-sm-12 col-md-12 col-xl-12">              
                <table class="table table-responsive-xl">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Promotion</th>
                            <th>Created At</th>
                            <th>Active?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" role="alert">
                            <td>1</td>
                            <td></td>
                            <td>Item 1</td>
                            <td>Miscellaneous</td>
                            <td>Description 1</td>
                            <td>1</td>
                            <td>SGD $1.15</td>
                            <td>80%</td>
                            <td>09 Mar 2023, 21:00:12</td>
                            <td>Active</td>

                            <!-- Close Button
                            <td>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa fa-close"></i></span>
                                </button>
                            </td>
                            -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>