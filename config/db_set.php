<?php
require 'error_handler.php';

// declaring class that stores the DB parameters.
// why constant? BECAUSE THEY ARE UNCHANGEABLE!!!

class DBParams {
    const ServerName = "localhost";
    const UserName = "root";
    const Password = '';
    const DatabaseName = "vivamus";
}

// defining error log file
// error files are also separated with a timestamp.
global $errorlogfile;
$errorlogfile = 'error_log'.date('YmdHis').'.txt';

// connect to database using try-catch
$conn = '';

try{
    $conn = new mysqli(DBParams::ServerName, DBParams::UserName, DBParams::Password, DBParams::DatabaseName);
} catch(Exception $e){

    // for debugging
    echo $e->getMessage();

    ExceptionToString($e, $errorlogfile);
    // let this function do the work.
    die("Connection failed");
}

// Check connection
if (!empty($conn->connect_error)) {
    ConnErrorToString($conn, $errorlogfile);
    die("Connection failed: ". $conn->connect_error);
}

// - - - - - - - - - - - - -
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

// check the initial generation (below is modified):
//  https://zzzcode.ai/sql/query-generator?id=4148b28f-4363-4bd8-903e-531e862eda5a

$SQL_info = "CREATE TABLE IF NOT EXISTS vivamus_info (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    mobile VARCHAR(15) NOT NULL
)";

$SQL_donate = "CREATE TABLE IF NOT EXISTS vivamus_donate (
    user_id INT(6) UNSIGNED NOT NULL,
    donation_amount DECIMAL(10, 2) NOT NULL,
    card_holder_name VARCHAR(100) NOT NULL,
    card_number VARCHAR(16) NOT NULL,
    card_security VARCHAR(4) NOT NULL,
    expiry_date DATE NOT NULL,

    FOREIGN KEY (user_id) REFERENCES vivamus_info(id)
)";

$SQL_comment = "CREATE TABLE IF NOT EXISTS vivamus_comment (
    anonymous BIT,
    anonymous_msg VARCHAR(200)
)";

// execute each statement separately
if (!$conn->query($SQL_info)) {
    ErrorLogCreator($conn->error, $errorlogfile);
    die("Error creating table vivamus_info: ". $conn->error);
}

if (!$conn->query($SQL_donate)) {
    ErrorLogCreator($conn->error, $errorlogfile);
    die("Error creating table vivamus_donate: ". $conn->error);
}

if (!$conn->query($SQL_comment)) {
    ErrorLogCreator($conn->error, $errorlogfile);
    die("Error creating table vivamus_comment: ". $conn->error);
} else {
    // returns nothing. only use for dev purpose.
    // echo "Tables created successfully<br><br>";
}
?>