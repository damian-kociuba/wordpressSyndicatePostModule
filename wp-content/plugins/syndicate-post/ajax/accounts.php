<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require_once realpath(__DIR__ . '/../PublishDriverManager.php');

$command = filter_input(INPUT_POST, 'command');

switch ($command) {
    case 'test_connection' : test_connection();
        break;
    default: echo 'Unknow command';
}

function test_connection() {
    try {
        $driverName = filter_input(INPUT_POST, 'driver_name');
        $parameters = json_decode(filter_input(INPUT_POST, 'driver_parameters'), true);
        $driverManager = new PublishDriverManager();
        $driver = $driverManager->getDriverByName($driverName);
        $driver->setRequiredParameters($parameters);


        echo json_encode($driver->testConnection());
    } catch (Exception $e) {
        echo json_encode(false);
    }
}
