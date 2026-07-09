<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Web Command Console</title>
    <style>
        body { background-color: #121212; color: #00FF00; font-family: monospace; padding: 20px; }
        #terminal { width: 100%; height: 400px; background-color: #000; border: 1px solid #333; padding: 10px; overflow-y: scroll; white-space: pre-wrap; box-sizing: border-box; margin-bottom: 10px; }
        #cmdInput { width: 80%; background: #222; border: 1px solid #555; color: #fff; padding: 10px; font-family: monospace; }
        #sendBtn { width: 18%; padding: 10px; background: #00FF00; color: #000; font-weight: bold; cursor: pointer; border: none; }
    </style>
</head>
<body>

    <h2>Remote Windows CMD Terminal</h2>
    <div id="terminal">Waiting for target system...</div>
    
    <input type="text" id="cmdInput" placeholder="Type a command (e.g., ipconfig, dir, whoami) and press Enter..." />
    <button id="sendBtn" onclick="sendCommand()">Execute</button>

    <script>
        // Check for new terminal outputs every 1 second
        setInterval(fetchOutput, 1000);

        function fetchOutput() {
            fetch('broker.php?action=get_output')
                .then(response => response.text())
                .then(data => {
                    if(data.trim() !== "") {
                        const term = document.getElementById('terminal');
                        // DECODE THE URL STRING: Vital to turn %20 and %0A back into spaces and lines
                        term.innerText += decodeURIComponent(data);
                        term.scrollTop = term.scrollHeight; // Auto scroll to bottom
                    }
                });
        }

        function sendCommand() {
            const inputField = document.getElementById('cmdInput');
            const command = inputField.value;
            if(!command) return;

            fetch('broker.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'command=' + encodeURIComponent(command)
            }).then(() => {
                inputField.value = ''; // Clear input field
            });
        }

        // Allow pressing Enter key to send command
        document.getElementById('cmdInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') sendCommand();
        });
    </script>
</body>
</html>
--!>
!-->
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<title>Web Message Console</title>

<style>

body {
    background-color:#121212;
    color:#00FF00;
    font-family:monospace;
    padding:20px;
}


#terminal {

    width:100%;
    height:400px;
    background:#000;
    border:1px solid #333;
    padding:10px;
    overflow-y:auto;
    white-space:pre-wrap;

}


#cmdInput {

    width:80%;
    background:#222;
    border:1px solid #555;
    color:white;
    padding:10px;
    font-family:monospace;

}


#sendBtn {

    width:18%;
    padding:10px;
    background:#00FF00;
    color:#000;
    font-weight:bold;
    cursor:pointer;
    border:none;

}


.online {
    color:#00ff00;
}


.offline {
    color:red;
}

</style>

</head>


<body>


<h2>Web Message Console</h2>


<div id="status">
Checking connection...
</div>


<div id="terminal">
Waiting for messages...
</div>


<input id="cmdInput"
placeholder="Type message..." />


<button id="sendBtn"
onclick="sendMessage()">
Send
</button>



<script>


let lastCount=0;


function print(text)
{
    let box=document.getElementById("terminal");

    box.innerHTML += text+"\n";

    box.scrollTop=box.scrollHeight;
}



async function sendMessage()
{

    let msg =
    document.getElementById("cmdInput").value;


    await fetch(
        "hi.php?action=send&from=browser",
        {
            method:"POST",
            body:msg
        }
    );


    document.getElementById("cmdInput").value="";

}



async function updateMessages()
{

    let r =
    await fetch("hi.php?action=get");


    let data =
    await r.json();


    if(data.length > lastCount)
    {

        for(let i=lastCount;i<data.length;i++)
        {

            print(
            "["+
            data[i].from+
            "] "+
            data[i].message
            );

        }


        lastCount=data.length;

    }

}



async function updateStatus()
{

    await fetch(
    "hi.php?action=status&name=browser"
    );


    let r =
    await fetch("hi.php?action=check");


    let data =
    await r.json();


    let now =
    Math.floor(Date.now()/1000);


    let text="";


    if(data.browser &&
       now-data.browser < 10)
    {
        text+="Browser: ONLINE\n";
    }
    else
    {
        text+="Browser: OFFLINE\n";
    }


    if(data.cpp &&
       now-data.cpp < 10)
    {
        text+="C++ App: ONLINE";
    }
    else
    {
        text+="C++ App: OFFLINE";
    }


    document.getElementById("status").innerText=text;

}



setInterval(updateMessages,2000);

setInterval(updateStatus,3000);


</script>


</body>
</html>
