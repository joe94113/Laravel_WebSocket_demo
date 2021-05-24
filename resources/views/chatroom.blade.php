<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>聊天室</title>
    <style type="text/css">
        #send {
            width: 200px;
            line-height: 50px;
            padding: 20px;
            border: 5px red solid;
            margin-right: 10px;
            float: left;
            display: inline;
        }

        #receive {
            width: 200px;
            line-height: 50px;
            padding: 20px;
            border: 5px blue solid;
            float: left;
            display: inline;
        }
    </style>
</head>

<body>
    <div>
        <input type="text" id="username" placeholder="輸入暱稱" autofocus>
        <input type="text" id="message" placeholder="請輸入訊息" autofocus>
        <input type="submit" value="發出訊息" onclick="send_messasge(); ">
    </div>
    <hr>
    <div id="send" class="send">
        您發出的訊息：
        <hr>
    </div>
    <div id="receive" class="receive">
        接收到的訊息：
        <hr>
    </div>
    <script>
        var wsServer = 'ws://127.0.0.1:8005'; // 指定websocket伺服器端位址
        var websocket = new WebSocket(wsServer); // 建立並連接至websocket伺服器
        websocket.onopen = function(event) {
            append_element('receive', '成功連接到 WebSocket 服務');
        };
        websocket.onclose = function(event) {
            append_element('receive', '關閉連接 WebSocket 服務');
        };
        websocket.onmessage = function(event) {
            append_element('receive', event.data);
        };
        websocket.onerror = function(event, error) {
            append_element('receive', event.data);
        };
        const send_messasge = function() { // 透過websocket將訊息發送給所有人
            var message = document.getElementById("message").value;
            document.getElementById("message").value = "";
            var user = document.getElementById("username").value;
            document.getElementById("username").value = "";
            var msg = user + "：" + message;
            append_element('send', msg);
            websocket.send(msg);
        };
        const append_element = function(ele_id, data) { // 將發出即收到訊息附加在html上的函數
            var parent = document.getElementById(ele_id);
            var p = document.createElement("p");
            p.innerText = data;
            parent.appendChild(p);
        };
    </script>
</body>

</html>