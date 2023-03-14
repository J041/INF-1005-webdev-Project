<?php
$cardtype = $cardnumber = $cardexpiration = $securitycode = 
        $fname = $lname = $error = "";
$success = true;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (!empty($_POST["lname"]))
    {
       $lname = sanitize_input($_POST["lname"]);
    }
    if (empty($_POST["fname"]))
    {
        $error .= "First name is required.<br>";
        $success = false;
    }
    else
    {
        $fname = sanitize_input($_POST["fname"]);
    }
    
    if (empty($_POST["cardtype"]))
    {
        $error .= "Please enter card type<br>";
        $success = false;
    }
    else
    {
        $cardtype = $_POST["cardtype"];
    }
    
    if (empty($_POST["securitycode"]))
    {
        $error .= "Please enter 3-digit card security code.<br>";
        $success = false;
    }
    else
    {
        $securitycode = $_POST["securitycode"];
        if (!is_numeric($securitycode) || strlen((string)$securitycode)!=3)
        {
            $error .= "Card security code must contain three digits.<br>";
            $success = false;
        }
    }

    if (empty($_POST["cardnumber"]))
    {
        $error .= "Please enter card number. <br>";
        $success = false;
    }
    else
    {
        $cardnumber = $_POST["cardnumber"];
        $cardnumber = str_replace("-","",$cardnumber);
        if (strlen($cardnumber)!=16 || !is_numeric($cardnumber))
        {
            $error .= "Card number must only contain 16-digits.<br>";
            $success = false;
        }
        
    }
    
    
    if (empty($_POST["cardexpiration"]))
    {
        $error .= "Please enter card expiration date.<br>";
        $success= false;
    }
    else
    {
        $cardexpiration = $_POST["cardexpiration"];
    }
}
else
{
    include "header.inc.php";
    include "nav.inc.php";
    echo "<main class='container'><h2>Error:</h2>";
    echo "<h4>Enter payment details <a href='payment.php'>here</a> for it to be processed</h4></main>";
    echo "<br><hr>";
    include "footer.inc.php";
    exit();
}


function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>




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
            <?php
                if ($success)
                {
                    echo "<h3>Transaction Successful!</h3>";
                    echo "<h4>Thank you for ordering with us " . $fname ." ". $lname.".</h4>";
                    echo "<a href='index.php'>Return to home.</a>";
                }
                else
                {
                    echo "<h3>Payment Unsuccessul</h3>";
                    echo "<h4>Please return to our payment page<a href='payment.php'> here </a>to resolve the following issues:</h4>";
                    echo $error;
                }
            ?>
        </main>
        <br>
        <hr>
        <?php
        include "footer.inc.php";
        ?>
    </body>
</html>



<!-- 
add to order history table?
remove from cart table?


-->
