<?php

use Workerman\Worker;
use Channel\Client;


require_once __DIR__ . '/vendor/autoload.php';  // require_once 在為主體 PHP 檔案包含進來的檔案僅能一次

$ws_worker = new Worker("websocket://127.0.0.1:8005"); // 建立websocket伺服器，port號為8005
$ws_worker->onWorkerStart = function ($worker) { // 向目前所有客戶端($worker->connections)發出廣播訊息
    Client::connect('127.0.0.1', 8006); // 連接到Channel伺服器。指定Port為8006。
    Client::on('broadcast', function ($event_data) use ($worker) { // 訂閱broadcast事件
        foreach ($worker->connections as $connection) { // 向當前worker進程的所有客戶端廣播訊息
            $connection->send($event_data);
        }
    });
};

$ws_worker->onMessage = function ($connection, $data) use ($ws_worker) {  // 當伺服器端收到資料後執行的函數
    Channel\Client::publish('broadcast', $data);  // 將訊息以廣播的方式發給目前所有客戶端，呼叫地12行函數進行廣播
};
$ws_worker->onClose = function ($connection) {  // 當客戶端連結關閉
    echo "連線已關閉\n";
};

Worker::runAll();
