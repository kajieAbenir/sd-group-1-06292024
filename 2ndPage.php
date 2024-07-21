<!-- 
 
DEV REFERENCE!

// SQL CREATION

// table: vivamus_info
    // first-name
    // last-name
    // email
    // mobile

// table: vivamus_donate
    // donation-amount
    // card-holder-name
    // card-number
    // card-security
    // expiry-date

// table: vivamus_comment
    // anonymous 

-->

<?php
// starts/continues session.
// just making sure lang.
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get the posted values
    $donation_amt = $_POST['donation-amt'];
    $cardholder_name = $_POST['card-holder-name'];
    $card_num = $_POST['card-number'];
    $css = $_POST['card-security'];
    $expiry = $_POST['expiry-date'];

    // Regular expression to match typical credit card number formats
    $card_regex = '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11})(?:[0-9]{3})?$/';

    // unlike in index.php, were doing regex here to compare credit card format authenticity (refer to ELSEIF statement below).

    if(empty($donation_amt) || empty($cardholder_name) || empty($card_num) || empty($css) || empty($expiry)){
        $error = 'Please fill in all the required fields.';
    } elseif(!filter_var($card_num,$card_regex)){
        $error = 'Invalid email address.';
    } else {
        // declare the query statement
        $query = "INSERT INTO vivamus_donate (donation_amt, cardholder_name, card_num, css, expiry) VALUES (?,?,?,?,?)";

        // prepares the statement in a variable and passes it to another variable
        $stmt = $db->prepare($query);

        // binds the paremeters
        $stmt->bind_param("sssss", $donation_amt, $cardholder_name, $card_num, $css, $expiry);

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
    <div class="header">
        <a href="index.html" class="logo">
            <img src="assets/img/vivamus_logo.png" alt="VIVAMUS Logo">
        </a>
        <div class="nav-links">
            <a href="#">Bibendum</a>
            <a href="#">Sollicitudin</a>
            <a href="#">Metus</a>
            <a href="#">Egestas</a>
        </div>
        <div class="search-box">
            <input type="text" placeholder="Search">
            <button type="button">&#128269;</button>
        </div>
    </div>

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

        <div class="form-container">
            <fieldset class="fieldset">
                <legend class="legend">Your donation</legend>
                <div class="input-group">
                    <label for="donation-amount">Donation Amount*</label>
                    <input type="text" id="donation-amount" name="donation-amount">
                </div>
                <div class="input-group">
                    <label for="card-holder-name">Card Holder Name*</label>
                    <input type="text" id="card-holder-name" name="card-holder-name">
                </div>
                <div class="input-group">
                    <label for="card-number">Credit Card No.*</label>
                    <input type="text" id="card-number" name="card-number">
                </div>
                <div class="input-group">
                    <label for="card-security-code">Card Security Code*</label>
                    <input type="text" id="card-security-code" name="card-security-code">
                </div>
                <div class="input-group">
                    <label for="expiry-date">Expiry Date*</label>
                    <input type="text" id="expiry-date" name="expiry-date">
                </div>
            </fieldset>
        </div>

        <div class="button-group">
            <button type="button">Back</button>
            <button type="button">Proceed</button>
        </div>
