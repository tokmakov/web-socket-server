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
    // анализируем поступившую команду и даем ответ
    if ( ! in_array($data, array('date', 'time', 'country', 'city'))) {
        WebSocketServer::response($connect, 'Неизвестная команда');
        return;
    }
    switch ($data) {
        case 'date'   : $response = date('d.m.Y'); break;
        case 'time'   : $response = date('H:i:s'); break;
        case 'country': $response = 'Россия';      break;
        case 'city'   : $response = 'Москва';      break;
    }
    WebSocketServer::response($connect, $response);
};

$server->startServer();