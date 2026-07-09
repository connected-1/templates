<?php

$message = file_get_contents("php://input");

if (!$message) {
    echo "ERROR: No data received";
    exit;
}

file_put_contents(
    "messages.txt",
    date("Y-m-d H:i:s") . " : " . $message . PHP_EOL,
    FILE_APPEND
);

echo "OK: Received -> " . $message;

?>
