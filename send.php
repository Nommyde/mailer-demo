<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/vendor/autoload.php';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

$channel = $connection->channel();
$channel->queue_declare('emails', false, false, false, false);

$msg = new AMQPMessage(json_encode([
    'type' => 'email',
    'to' => 'rus@tam.pw',
    'from' => 'bla@bla.ru',
    'subject' => 'Test',
    'message' => 'Random number is ' . mt_rand(1, 100),
]));

$channel->basic_publish($msg, '', 'emails');

echo "sent\n";

$channel->close();
$connection->close();
