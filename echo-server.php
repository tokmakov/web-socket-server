<?php 
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

require 'WebSocketServer.class.php';

$server = new WebSocketServer('127.0.0.1', 7777);
// максимальное время работы 100 секунд, выводить сообщения в консоль
$server->settings(100, true);

// эта функция будет вызвана, когда получено сообщение от клиента
$server->handler = function($connect, $data) {
    // полученные от клиента данные отправляем обратно
    WebSocketServer::response($connect, $data);
};

$server->startServer();