<?php
require 'error_handler.php';

// defining error log file
// error files are also separated with a timestamp.
$errorlogfile = 'error_log'.date('YmdHis').'.txt';

// setup database connection parameters.
$ServerName = "localhost";
$UserName = "root";
$Password = "";
$DatabaseName = "vivamus";

// connect to database using try-catch
try{
    $conn = new mysqli($ServerName, $UserName, $Password, $DatabaseName);
} catch(Exception $e){
    ExceptionToString($e, $errorlogfile);
    // let this function do the work.
}

// Check connection
if ($conn->connect_error) {
    // letting this function handle error saving before 'die'-ing the connection.
    ConnErrorToString($conn, $errorlogfile);

    die("Connection failed: " . $conn->connect_error);
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

$SQL = "CREATE TABLE IF NOT EXISTS vivamus_info (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    mobile VARCHAR(15) NOT NULL
);

CREATE TABLE IF NOT EXISTS vivamus_donate (
    user_id INT NOT NULL,
    donation_amount DECIMAL(10, 2) NOT NULL,
    card_holder_name VARCHAR(100) NOT NULL,
    card_number VARCHAR(16) NOT NULL,
    card_security VARCHAR(4) NOT NULL,
    expiry_date DATE NOT NULL,

    FOREIGN KEY (info_id) REFERENCES vivamus_info(id)
);

CREATE TABLE IF NOT EXISTS vivamus_comment (
    anonymous BIT,
    anonymous_msg VARCHAR(200)
)";

// execute statement and check if table creation is okay
if ($conn->query($sql) !== TRUE) {
    ErrorLogCreator($conn->error, $errorlogfile);
    die("Error creating table: " . $conn->error);
}
?>