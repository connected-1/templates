
<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if($_SERVER['REQUEST_METHOD']=="OPTIONS")
{
    exit;
}

$file="messages.json";
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



if($action=="send")
{
    // Read raw body
    $msg=file_get_contents("php://input");


    if($msg===false || strlen($msg)==0)
    {
        echo "ERROR_EMPTY";
        exit;
    }


    $messages=loadJson($file);

    $messages[]=$msg;

    saveJson($file,$messages);


    echo "OK";
    exit;
}



if($action=="get")
{
    echo json_encode(
        loadJson($file),
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    exit;
}



if($action=="latest")
{
    $messages=loadJson($file);

    if(count($messages)>0)
        echo end($messages);

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
// $file="messages.json";
// $statusFile="status.json";
// $action=$_GET["action"]??"";

// if($action=="send")
// {
//     $msg=file_get_contents("php://input");

//     if(!$msg)
//     {
//         echo "ERROR";
//         exit;
//     }

//     $messages=[];

//     if(file_exists($file))
//         $messages=json_decode(file_get_contents($file),true);

//     $messages[]=$msg;

//     file_put_contents($file,json_encode($messages));

//     echo "OK";
//     exit;
// }

// if($action=="get")
// {
//     if(!file_exists($file))
//     {
//         echo "[]";
//         exit;
//     }

//     echo file_get_contents($file);
//     exit;
// }

// if($action=="latest")
// {
//     if(!file_exists($file))
//     {
//         echo "";
//         exit;
//     }

//     $messages=json_decode(file_get_contents($file),true);

//     if(count($messages)>0)
//         echo end($messages);

//     exit;
// }

// if($action=="status")
// {
//     $name=$_GET["name"]??"unknown";

//     $status=[];

//     if(file_exists($statusFile))
//         $status=json_decode(file_get_contents($statusFile),true);

//     $status[$name]=time();

//     file_put_contents($statusFile,json_encode($status));

//     echo "OK";
//     exit;
// }

// if($action=="check")
// {
//     if(!file_exists($statusFile))
//     {
//         echo "{}";
//         exit;
//     }

//     echo file_get_contents($statusFile);
//     exit;
// }

