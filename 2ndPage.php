<!-- 
 
DEV REFERENCE!

// SQL CREATION

// table: vivamus_info
    // first_name
    // last_name
    // email
    // mobile

// table: vivamus_donate
    // donation_amount
    // card_holder_name
    // card_number
    // card_security
    // expiry_date

// table: vivamus_comment
    // anonymous
    // anonymous_msg

-->

<?php

// just making sure lang.
session_start();

include 'config/db_set.php';

// declaring instantiation of the class.
$DBParams = new DBParams();

// Establish database connection
$__db = new mysqli(DBParams::ServerName, DBParams::UserName, DBParams::Password, DBParams::DatabaseName);

// Check connection
if ($__db->connect_error) {
    die("Connection failed: " . $__db->connect_error);
}

// remove after debug.

echo 'POST contents:<br>';

var_dump($_POST);

echo 'REQUEST contents:<br>';

var_dump($_REQUEST);

// the usual POST method.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // get the posted values
    $donation_amt = $_POST['donation-amount'];
    $cardholder_name = $_POST['card-holder-name'];
    $card_num = $_POST['card-number'];
    $css = $_POST['card-security'];
    $expiry = $_POST['expiry-date'];

    // Regular expression to match typical credit card number formats

    // Here's a breakdown of what it scans:

    // ^ asserts the start of the string.
    // (?:) is a non-capturing group that contains the pattern for matching different types of credit card numbers.

    // The pattern matches the following types of credit card numbers:

    //      4[0-9]{12}(?:[0-9]{3})? matches Visa card numbers, which start with a 4 and have 12 or 15 digits.
    //      5[1-5][0-9]{14} matches Mastercard numbers, which start with a 51-55 and have 16 digits.
    //      6(?:011|5[0-9][0-9])[0-9]{12} matches Discover card numbers, which start with a 6011 or 65 and have 16 digits.
    //      3[47][0-9]{13} matches American Express card numbers, which start with a 34 or 37 and have 15 digits.
    //      3(?:0[0-5]|[68][0-9])[0-9]{11} matches Diners Club card numbers, which start with a 300-305 or 36 or 38 and have 14 digits.

    // (? :[0-9]{3})? is an optional group that matches a 3-digit security code (CVV).

    // $ asserts the end of the string.

    $card_regex = '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11})(?:[0-9]{3})?$/';

    // unlike in index.php, were doing regex here to compare credit card format authenticity (refer to ELSEIF statement below).

    // bug: $_POST is empty.
    if(empty($donation_amt) || empty($cardholder_name) || empty($card_num) || empty($css) || empty($expiry)){
        $_SESSION['error'] = 'Please fill in all the required fields.';

        // Recursor to this page.
        header('Location: 2ndPage.php');
    } elseif(!filter_var($card_num,$card_regex)){
        $_SESSION['error'] = 'Invalid debit/credit card format.';

        // Recursor to this page.
        header('Location: 2ndPage.php');
    } else {
        // this method gets the LAST entry id key.
        // to make sure na the data gets connected.

        $email = $_SESSION['email'];

        $SQL_last_id = 
        "   SELECT MAX(id) AS last_id 
            FROM vivamus_info 
            WHERE email = ?
        ";

        $stmt = $__db->prepare($SQL_last_id);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // defining last SQL ID holder
        $last_id = '';

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $last_id = $row['last_id'];

            // remove after debug.
            echo "<br>Last ID for email $email: $last_id";
        } else {
            echo "No rows found for email $email";
        }

        // declare the query statement
        $query = "INSERT INTO vivamus_donate (user_id, donation_amount, card_holder_name, card_number, card_security, expiry_date) VALUES (?,?,?,?,?,?)";

        // prepares the statement in a variable and passes it to another variable
        $stmt = $__db->prepare($query);

        // binds the paremeters
        $stmt->bind_param("idssss", $last_id, $donation_amt, $cardholder_name, $card_num, $css, $expiry);

        // tries to execute the statement.
        try{
            $stmt->execute();
        } catch(Exception $e) {
            // creates log file.
            ExceptionToString($e, $errorlogfile);
        }

        $stmt->close();

        // Redirect to the next step
        header('Location: 3rdPage.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Form</title>
    
    <!-- link to content styling. -->
    <link rel="stylesheet" href="assets/css/index-styles.css">

    <!-- always include/'link' these. for header & footer styling -->
    <link rel="stylesheet" href="assets/css/header-styles.css">
    <link rel="stylesheet" href="assets/css/footer-styles.css">
</head>
<body>
    <script src="assets/script/search-script.js"></script>

    <!-- header. template. copy to another (scrapping the header/footer idea) -->
    <div class="header">
        <a href="index.html" class="logo">
            <img src="assets/img/vivamus_logo.png" alt="VIVAMUS Logo">
        </a>
        <div class="nav-links">
            <a href="redirect.html">Bibendum</a>
            <a> | </a>
            <a href="redirect.html">Sollicitudin</a>
            <a> | </a>
            <a href="redirect.html">Metus</a>
            <a> | </a>
            <a href="redirect.html">Egestas</a>
        </div>
        <div class="search-box">
            <input type="text" id="search-input" placeholder="Search">
            <button type="submit" onclick="search()">
                <img src="assets/img/search_icon.png" alt="Search">
            </button>
        </div>
    </div>

    <!-- ERROR SECTION -->
    <?php
        if (isset($_SESSION['error'])) {
            echo '<div style="background-color: #720002; text-align: center;"> <p style="color: white; font-weight: bold; font-size:3.2vh;">' . $_SESSION['error'] . '</p></div>';

            unset($_SESSION['error']);
        }
    ?>

    <div class="container">
        <h1>Vivamus interdum nunc ac sem fringilla</h1>
        <p>Duis risus urna, mattis eget justo non, gravida ultrices diam</p>
        
        <div class="progress-bar">
            <div class="step">
                <a class="circle">1</a>
            </div>
            <div class="step active">
                <div class="circle">2</div>
            </div>
            <div class="step">
                 <a class="circle">3</a>
            </div>
        </div>

        <form class="form-container" method="post" enctype="multipart/form-data">
            <fieldset class="fieldset">
                <legend class="legend" style="font-weight: bold;">Your donation</legend>
                <div class="input-group">
                    <label for="donation-amount">Donation Amount <a style="color: red;">*</a></label>
                    <input type="text" id="donation-amount" name="donation-amount" required>
                </div>
                <div class="input-group">
                    <label for="card-holder-name">Card Holder Name <a style="color: red;">*</a></label>
                    <input type="text" id="card-holder-name" name="card-holder-name" required>
                </div>
                <div class="input-group">
                    <label for="card-number">Credit Card No. <a style="color: red;">*</a></label>
                    <input type="text" id="card-number" name="card-number" required>
                </div>
                <div class="input-group">
                    <label for="card-security-code">Card Security Code <a style="color: red;">*</a></label>
                    <input type="text" id="card-security-code" name="card-security-code" maxlength="3" required>
                </div>
                <div class="input-group">
                    <label for="expiry-date">Expiry Date <a style="color: red;">*</a></label>
                    <input type="text" id="expiry-date" name="expiry-date" required>
                </div>
            </fieldset>

            <div class="button-group">
                <button type="button" onclick="location.href='index.php'">Back</button>
                <button type="submit">Proceed</button>
            </div>
        </form>

    
        
    </div>

    <footer>
        <div class="footer-nav">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact</a>
        </div>
        <p>&copy; 2024 Vivamus. All rights reserved.</p>
    </footer>
</body>
</html>
