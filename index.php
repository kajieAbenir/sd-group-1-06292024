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

session_start();

// this is to ensure this is booted.
require_once 'config/config.php';

// declaring instantiation of the class.
$DBParams = new DBParams();

// Create a connection to the database
$conn = new mysqli(DBParams::ServerName, DBParams::UserName, DBParams::Password, DBParams::DatabaseName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// pass connection to $__db
$__db = $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted values
    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];

    $conn = null;

    // Validate the input values
    if (empty($first_name) || empty($last_name) || empty($email) || empty($mobile)) {
        $_SESSION['error'] = 'Fill in all required fields';

        // Recursor to this page.
        header('Location: index.php');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email!';

        // Recursor to this page.
        header('Location: index.php');
    } else if (!(strlen($mobile) == 11 && (substr($mobile, 0, 2) == '09' || substr($mobile, 0, 4) == '+639'))) {
        $_SESSION['error'] = 'Mobile number is invalid. It must start with \'09\' or \'+639\' and have exactly 11 digits.';

        // Recursor to this page.
        header('Location: index.php');

    } else {
        // declare the query statement
        $query = "INSERT INTO vivamus_info (first_name, last_name, email, mobile) VALUES (?,?,?,?)";

        // prepares the statement in a variable and passes it to another variable
        $stmt = $__db->prepare($query);

        // binds the paremeters
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $mobile);

        // tries to execute the statement.
        try{
            $stmt->execute();
        } catch(Exception $e) {
            // creates log file.
            ExceptionToString($e, $errorlogfile);
        }

        $stmt->close();

        // moving to the session variable.

        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;
        $_SESSION['mobile'] = $mobile;

        // Redirect to the next step
        header('Location: 2ndPage.php');
        exit;
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VIVAMUS - HOME</title>

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
        }
    ?>

    <div class="poppins-regular container">
        <h1 class="poppins-bold">In euismod sapien eu maximus tempus</h1>
        <p>Vestibulum bibendum posuere dui, in pharetra est hendrerit ac. Integer posuere metus lacus</p>

        <div class="progress-bar no-select">
            <div class="step active">
                <div class="circle">1</div>
            </div>
            <!-- JERNEL SERAT TAGA KALUBIHAN TALAMBAN AYAW PAG-HREF KAY MADEFEAT ANG PURPOSE NA STEP-BY-STEP PROCESS SIYA -->
            <div class="step">
                <a class="circle">2</a>
            </div>
            <div class="step">
                 <a class="circle">3</a>
            </div>
        </div>

        <form class="form-container no-select" method="post">
            <fieldset>
                <legend>About you</legend>
                <div class="input-group">
                    <label for="first-name">First name <a style="color: red;">*</a></label>
                    <input type="text" id="first-name" name="first-name" required>
                </div>
                <div class="input-group">
                    <label for="last-name">Last name <a style="color: red;">*</a></label>
                    <input type="text" id="last-name" name="last-name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email Address <a style="color: red;">*</a></label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="mobile">Mobile number <a style="color: red;">*</a></label>
                    <input type="tel" id="mobile" name="mobile" maxlength="11" required>
                </div>
                <button type="submit">Next</button>
            </fieldset>
        </form>
    </div>

    <footer>
        <div class="footer-nav">
            <a href="#">Pellentesque</a>
            <a href="#">Et interdum</a>
            <a href="#">Neque</a>
            <a href="#">Integer</a>
            <a href="#">Ullamcorper</a>
            <a href="#">Sagittis</a>
        </div>
        <p>&copy; 2015 Proin eget ipsum libero. All Rights Reserved.</p>
    </footer>
</body>
</html>
