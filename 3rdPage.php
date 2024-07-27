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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anonymous_flag = $_POST['anonymous'];
    $message = $_POST['message'];
    
    if (isset($_POST['anonymous']) && !empty($_POST['anonymous'])) {
        // The checkbox is ticked
        $anonymous_flag = 1;
    } else {
        // The checkbox is not ticked
        $anonymous_flag = 0;
    }

    if(!empty($message)){
        // declare the query statement
        $query = "INSERT INTO vivamus_comment (anonymous, anonymous_msg) VALUES (?,?)";

        // prepares the statement in a variable and passes it to another variable
        $stmt = $__db->prepare($query);

        // binds the paremeters
        $stmt->bind_param("is", $anonymous_flag, $message);

        // tries to execute the statement.
        try{
            $stmt->execute();
        } catch(Exception $e) {
            // creates log file.
            ExceptionToString($e, $errorlogfile);
        }

        $stmt->close();

        // destroy the session because the 'donation' has been done.
        session_unset();
        session_destroy();

        // Redirect to the next step
        header('Location: redirect.html');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Design</title>
    
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

    <div class="container">
        <h1>In euismod sapien eu maximus tempus.</h1>
        <p>Vestibulum bibendum posuere dui, in pharetra est hendrerit ac. Integer posuere metus lacus</p>
        
        <div class="progress-bar">
            <div class="step">
                <a class="circle">1</a>
            </div>
            <div class="step">
                <a class="circle">2</a>
            </div>
            <div class="step active">
                <div class="circle">3</div>
            </div>
        </div>

        <form class="form-container" method="post">
            <fieldset class="fieldset">
                <legend class="legend">Comment Field</legend>
                <div class="input-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message"></textarea>
                </div>
                <div class="input-group">
                    <input type="checkbox" id="anonymous" name="anonymous">
                    <label for="anonymous">Keep me anonymous</label>
                </div>
            </fieldset>

            <div class="button-group">
                <button type="button">Back</button>
                <button type="submit">Finish</button>
            </div>
        </form>

        
    </div>

    <footer>
        <div class="footer-nav">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact</a>
        </div>
        <p>&copy; 2025 Proin eget ipsum libero. All Rights Reserved.</p>
    </footer>
</body>
</html>
