<?php

namespace Nommyde\Tests;


use Nommyde\MailerInterface;
use Nommyde\MailingConsumer;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MailingConsumerTest extends TestCase
{
    /**
     * @var MockObject
     */
    protected $mailer;

    /**
     * @var AMQPMessage
     */
    protected $message;

    public function setUp()
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->message = new AMQPMessage();
    }

    public function testSuccessConsume()
    {
        $this->mailer->expects($this->once())->method('send');
        $this->message->body = json_encode(['type' => 'email']);

        /** @noinspection PhpParamsInspection */
        $consumer = new MailingConsumer($this->mailer);
        $consumer->consume($this->message);
    }

    public function testFailureConsume()
    {
        $this->mailer->expects($this->never())->method('send');
        $this->message->body = json_encode(['type' => 'other']);

        /** @noinspection PhpParamsInspection */
        $consumer = new MailingConsumer($this->mailer);
        $consumer->consume($this->message);
    }
}
