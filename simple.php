<?php
function SocketServer($limit = 0) {
    $starttime = time();
    echo 'SERVER START' . PHP_EOL;

    echo 'Socket create...' . PHP_EOL;
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

    if (false === $socket) {
        die('Error: ' . socket_strerror(socket_last_error()) . PHP_EOL);
    }

    echo 'Socket bind...' . PHP_EOL;
    $bind = socket_bind($socket, '127.0.0.1', 7777); // привязываем к ip и порту
    if (false === $bind) {
        die('Error: ' . socket_strerror(socket_last_error()) . PHP_EOL);
    }

    echo 'Set options...' . PHP_EOL;
    // разрешаем использовать один порт для нескольких соединений
    $option = socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
    if (false === $option) {
        die('Error: ' . socket_strerror(socket_last_error()) . PHP_EOL);
    }

    echo 'Listening socket...' . PHP_EOL;
    $listen = socket_listen($socket); // слушаем сокет
    if (false === $listen) {
        die('Error: ' . socket_strerror(socket_last_error()) . PHP_EOL);
    }

    while (true) { // бесконечный цикл ожидания подключений
        echo 'Waiting for connections...' . PHP_EOL;
        $connect = socket_accept($socket); // зависаем, пока не получим ответа
        if ($connect !== false) {
            echo 'Client connected...' . PHP_EOL;
            echo 'Send message to client...' . PHP_EOL;
            socket_write($connect, 'Hello, Client!');
        } else {
            echo 'Error: ' . socket_strerror(socket_last_error()) . PHP_EOL;
            usleep(1000);
        }

        // останавливаем сервер после $limit секунд
        if ($limit && (time() - $starttime > $limit)) {
            echo 'Closing connection...' . PHP_EOL;
            socket_close($socket);
            echo 'SERVER STOP' . PHP_EOL;
            return;
        }
    }
}

error_reporting(E_ALL); // выводим все ошибки и предупреждения
set_time_limit(0);      // бесконечное время работы скрипта
ob_implicit_flush();    // включаем вывод без буферизации

// Запускаем сервер в работу, завершение работы через 60 секунд
SocketServer(60);
