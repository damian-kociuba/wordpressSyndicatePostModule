<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
require_once '../SpinnerChief.php';
require_once '../../../../wp-includes/class-phpmailer.php';
$command = filter_input(INPUT_POST, 'command');

switch ($command) {
    case 'spinner_chief_test_connection' : spinnerCheifTestConnection();
        break;
    case 'mail_test': mail_test();
        break;
    default: echo 'Unknow command';
}

function spinnerCheifTestConnection() {
    $url = filter_input(INPUT_POST, 'url');
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    $apiKey = filter_input(INPUT_POST, 'api_key');

    $spinnerChief = new SpinnerChief();
    $spinnerChief->setAddress($url);
    $spinnerChief->setUsername($username);
    $spinnerChief->setPassword($password);
    $spinnerChief->setApikey($apiKey);

    try {
        if ($spinnerChief->testConnection()) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    } catch (Exception $e) {
        echo json_encode(false);
    }
}

function mail_test() {
    $host = filter_input(INPUT_POST, 'host');
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    $port = filter_input(INPUT_POST, 'port');
    $to = filter_input(INPUT_POST, 'to');
    $smtpSecure = filter_input(INPUT_POST, 'smtp_secure');

    $mailer = new PHPMailer();
    $mailer->isSMTP();
    $mailer->SMTPAuth = true;
    $mailer->SMTPSecure = $smtpSecure;
    $mailer->Host = $host;
    $mailer->Username = $username;
    $mailer->Password = $password;
    $mailer->Port = $port;

    $mailer->From = $username;
    $mailer->FromName = $username;
    $mailer->addReplyTo($username, $username);
    $mailer->addAddress($to);
    $mailer->Subject = 'Test mail';
    $mailer->msgHTML('Test message');
    echo json_encode( $mailer->send());
}
