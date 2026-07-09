<?php

$file="messages.json";
$statusFile="status.json";
$action=$_GET["action"]??"";


if($action=="send")
{
    $msg=file_get_contents("php://input");

    if(!$msg){echo "ERROR";exit;}

    $messages=[];

    if(file_exists($file))
        $messages=json_decode(file_get_contents($file),true);

    $messages[]=$msg;

    file_put_contents($file,json_encode($messages));

    echo "OK";
    exit;
}


if($action=="get")
{
    if(!file_exists($file))
    {
        echo "[]";
        exit;
    }

    echo file_get_contents($file);
    exit;
}


if($action=="status")
{
    $name=$_GET["name"]??"unknown";

    $status=[];

    if(file_exists($statusFile))
        $status=json_decode(file_get_contents($statusFile),true);

    $status[$name]=time();

    file_put_contents($statusFile,json_encode($status));

    echo "OK";
    exit;
}


if($action=="check")
{
    if(!file_exists($statusFile))
    {
        echo "{}";
        exit;
    }

    echo file_get_contents($statusFile);
    exit;
}

?>
