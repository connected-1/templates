<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");


$file = "data.json";


if (!file_exists($file)) {

    file_put_contents(
        $file,
        json_encode([
            "online" => false,
            "last_seen" => 0,
            "messages" => [],
            "responses" => []
        ])
    );

}


$data = json_decode(
    file_get_contents($file),
    true
);



$action = $_GET["action"] ?? "";



// Browser -> C++
if ($action == "send") {


    $msg = $_POST["msg"] ?? "";


    $data["messages"][] =
        base64_encode($msg);



    file_put_contents(
        $file,
        json_encode($data)
    );


    echo "OK";

    exit;

}




// C++ receives message
if ($action == "receive") {


    if (count($data["messages"]) > 0) {


        $msg =
        array_shift(
            $data["messages"]
        );


        file_put_contents(
            $file,
            json_encode($data)
        );


        echo $msg;

    }


    exit;

}





// C++ -> Browser
if ($action == "reply") {


    $msg =
    $_POST["msg"] ?? "";



    $data["responses"][] =
    base64_encode($msg);



    $data["online"] = true;

    $data["last_seen"] = time();



    file_put_contents(
        $file,
        json_encode($data)
    );


    echo "OK";

    exit;

}





// Browser display
if ($action == "status") {


    if (
        time() -
        $data["last_seen"]
        > 10
    ) {

        $data["online"] = false;

    }



    foreach(
        $data["responses"]
        as &$msg
    ){

        $msg =
        base64_decode($msg);

    }



    echo json_encode($data);


    exit;
    

}
<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");


$file = "data.json";


if (!file_exists($file)) {

    file_put_contents(
        $file,
        json_encode([
            "online" => false,
            "last_seen" => 0,
            "messages" => [],
            "responses" => []
        ])
    );

}


$data = json_decode(
    file_get_contents($file),
    true
);



$action = $_GET["action"] ?? "";



// Browser -> C++
if ($action == "send") {


    $msg = $_POST["msg"] ?? "";


    $data["messages"][] =
        base64_encode($msg);



    file_put_contents(
        $file,
        json_encode($data)
    );


    echo "OK";

    exit;

}




// C++ receives message
if ($action == "receive") {


    if (count($data["messages"]) > 0) {


        $msg =
        array_shift(
            $data["messages"]
        );


        file_put_contents(
            $file,
            json_encode($data)
        );


        echo $msg;

    }


    exit;

}





// C++ -> Browser
if ($action == "reply") {


    $msg =
    $_POST["msg"] ?? "";



    $data["responses"][] =
    base64_encode($msg);



    $data["online"] = true;

    $data["last_seen"] = time();



    file_put_contents(
        $file,
        json_encode($data)
    );


    echo "OK";

    exit;

}





// Browser display
if ($action == "status") {


    if (
        time() -
        $data["last_seen"]
        > 10
    ) {

        $data["online"] = false;

    }



    foreach(
        $data["responses"]
        as &$msg
    ){

        $msg =
        base64_decode($msg);

    }



    echo json_encode($data);


    exit;

}


?><?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");


$file = "data.json";


if (!file_exists($file)) {

    file_put_contents(
        $file,
        json_encode([
            "online" => false,
            "last_seen" => 0,
            "messages" => [],
            "responses" => []
        ])
    );

}


$data = json_decode(
    file_get_contents($file),
    true
);



$action = $_GET["action"] ?? "";



// Browser -> C++
if ($action == "send") {


    $msg = $_POST["msg"] ?? "";


    $data["messages"][] =
        base64_encode($msg);



    file_put_contents(
        $file,
        json_encode($data)
    );


    echo "OK";

    exit;

}




// C++ receives message
if ($action == "receive") {


    if (count($data["messages"]) > 0) {


        $msg =
        array_shift(
            $data["messages"]
        );


        file_put_contents(
            $file,
            json_encode($data)
        );


        echo $msg;

    }


    exit;

}





// C++ -> Browser
if ($action == "reply") {


    $msg =
    $_POST["msg"] ?? "";



    $data["responses"][] =
    base64_encode($msg);



    $data["online"] = true;

    $data["last_seen"] = time();



    file_put_contents(
        $file,
        json_encode($data)
    );


    echo "OK";

    exit;

}





// Browser display
if ($action == "status") {


    if (
        time() -
        $data["last_seen"]
        > 10
    ) {

        $data["online"] = false;

    }



    foreach(
        $data["responses"]
        as &$msg
    ){

        $msg =
        base64_decode($msg);

    }



    echo json_encode($data);


    exit;

}


?>


?>
