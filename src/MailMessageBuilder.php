<?php

namespace Nommyde;


use PhpAmqpLib\Message\AMQPMessage;

class MailMessageBuilder
{
    /**
     * @param AMQPMessage $amqpMessage
     * @return MailMessage
     * @throws \Exception
     */
    public static function fromAmqp(AMQPMessage $amqpMessage): MailMessage
    {
        $object = json_decode($amqpMessage->body);

        if (!isset($object->type) || $object->type !== 'email') {
            throw new \Exception('invalid message type');
        }


        $message = new MailMessage();
        $message->from = strval($object->from ?? '');
        $message->to = strval($object->to ?? '');
        $message->subject = strval($object->subject ?? '');
        $message->body = strval($object->message ?? '');

        return $message;
    }
}
