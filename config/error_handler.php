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
    try{
        $fp = fopen($ErrLogfile, 'w'); 
        // 'w' for write
        // since the files are separated with timestamps, this 'file setting' is appropriate

        // write contents into the file.
        fwrite($fp, $string);

        // close file pointer to save RAM.
        fclose($fp);
    } catch(Exception $e){
        // if there is an error, this will be caught.
        // this will also be print out in the console.
        error_log($e);
    }
    
}
?>