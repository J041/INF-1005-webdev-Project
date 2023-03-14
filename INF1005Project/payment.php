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
        
        <main class="container paymentform">
            <h2>Payment</h2>
            <form action="process_payment.php" method="post">
                <div class="form-group">
                        <label for="cardtype">Credit Card Type</label><br>
                        <select id="cardtype" name="cardtype" required>
                            <option value=""></option>
                            <option value="visa">VISA</option>
                            <option value="mastercard">MasterCard</option>
                        </select>
                </div>
                 <div class="form-group">
                        <label for="cardnumber">Credit Card Number</label><br>
                        <input class="form-control"  type="text" id="cardnumber" name="cardnumber"
                               required pattern="\d{4}(?:-\d{4})*" placeholder="0000-0000-0000-0000"
                               onkeyup=" CreditCardFormat()" minlength="19" maxlength="19">
                </div>
                <div id="creditcardinfo">

                    <div class="form-group">
                        <label for="cardexpiration">Expiration Date</label><br>
                        <input class="form-control" type="date" id="cardexpiration" name="cardexpiration"
                               required min="2023-03-01" max="2028-12-01">
                    </div>
                    <div class="form-group">
                        <label for="securitycode">Security Code</label><br>
                        <input class="form-control" type="text" id="securitycode" name="securitycode"
                               required pattern="[0-9]{3}" placeholder="xxx" maxlength="3">
                    </div>
                </div>
                <div id="cardname">
                    <div class="form-group">
                        <label for="fname">First Name</label><br>
                        <input class="form-control" type="text" id="fname" name="fname"
                               required maxlength="45" placeholder="Enter first name">
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label><br>
                        <input class="form-control" type="text" id="lname" name="lname"
                               maxlength="45" placeholder="Enter last name">
                    </div>
                </div>
                <br>
                <div id="paybtn">
                    <div class="form-group">
                    <a href="checkout.php" class="btn btn-danger" id="cancelpayment">Cancel</a>
                    </div>
                    <div class="form-group">
                    <button class="btn btn-success" type="submit" id="pay">Confirm Payment</button>
                    </div>
                </div>
                
            </form>
        </main> 
        <br>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>