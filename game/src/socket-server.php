<?php

use Ratchet\Server\IoServer;
use lobby\lobbyCode;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require dirname(__DIR__) . '/vendor/autoload.php';

$server = IoServer::factory(
    
    new HttpServer(
        new WsServer(
            new lobbyCode()
        )
    ),
    1337
    
);

$server->run();