<?php

namespace Nommyde;


use PhpAmqpLib\Message\AMQPMessage;

interface AmqpConsumerInterface
{
    public function consume(AMQPMessage $message);
}
