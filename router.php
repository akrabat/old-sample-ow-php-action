<?php
const ACTION_SRC = 'action.php';

try {
    ob_start();

    switch ($_SERVER["REQUEST_URI"]) {
        case "/init":
            // Nothing to return.
            header('Content-Length: 3');
            echo "OK\n";
            break;

        case "/run":
            //  load the code, assume function "main"
            require ACTION_SRC;

            // function arguments are in the POSTed data's "value" field
            $post = file_get_contents('php://input');
            $data = json_decode($post, true);

            // run the action
            ob_start();
            $result = main($data["value"]);
            $logs = ob_get_clean();
            
            // write anything that was echo'd in the action to the log
            error_log($logs);

            // Return.
            header('Content-Type: application/json');
            echo json_encode($result, JSON_FORCE_OBJECT);
    }

    $output = ob_get_contents();
    header("Content-Length: " . mb_strlen($output));
    echo $output;
} catch (Throwable $e) {
    error_log((string)$e);
}

// Convert errors to exceptions
set_error_handler(function ($level, $message, $file, $line) {
    if (!(error_reporting() & $level)) {
        return;
    }
    throw new ErrorException($message, 0, $level, $file, $line);
});
