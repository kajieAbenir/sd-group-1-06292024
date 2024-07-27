<?php
// SEPARATE FILE FOR ALL ERROR LOGGING.
// PLEASE DO NOT REMOVE. ANY CHANGES WOULD BE FATAL.

// this converts the Exception into a string.
function ExceptionToString(Exception $e, $ErrLogFile){
    // saves the converted into a variable.
    $error = (string) $e->getMessage();

    // let this function handle work.
    ErrorLogCreator($error, $ErrLogFile);
}

// this converts the Connection error bits into a string.
function ConnErrorToString($conn,$ErrLogFile){
    // stores & converts whatever in this connect_error into string
    $errorString = (string) $conn->connect_error;

    // passes to this function.
    ErrorLogCreator($errorString, $ErrLogFile);
}

function ErrorLogCreator($string, $ErrLogfile){
    $logDir = 'logs'; // specify the log directory
    $logFile = $logDir . '/' . $ErrLogfile; // construct the full log file path

    // check if the log directory doesn't exist
    if (!is_dir($logDir)) {
        // create the log directory if it doesn't exist
        mkdir($logDir, 0777, true);
    }

    try {
        $fp = fopen($logFile, 'w'); 
        fwrite($fp, $string);
        fclose($fp);
    } catch (Exception $e) {
        echo($e);
    }
}

?>