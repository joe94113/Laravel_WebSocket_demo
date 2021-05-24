<?php

use Workerman\Worker;
use Channel\Client;
use Channel\Server;

require_once __DIR__ . '/vendor/autoload.php';

$channel_server = new Server('127.0.0.1', 8006);  // 建立一個Channel伺服器，指定Port為8006
Worker::runAll();
