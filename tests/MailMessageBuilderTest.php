<?php

namespace Nommyde\Tests;


use Nommyde\MailMessage;
use Nommyde\MailMessageBuilder;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;

class MailMessageBuilderTest extends TestCase
{
    public function testFromAmqpSuccess()
    {
        $message = new AMQPMessage();
        $message->body = json_encode(['type' => 'email', 'from' => 'A', 'to' => 'B', 'subject' => 'S', 'message' => 'M']);

        /** @noinspection PhpUnhandledExceptionInspection */
        $mailMessage = MailMessageBuilder::fromAmqp($message);

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        $this->assertSame('M', $mailMessage->body);
        $this->assertSame('S', $mailMessage->subject);
        $this->assertSame('B', $mailMessage->to);
        $this->assertSame('A', $mailMessage->from);
    }

    public function testFromAmqpFailure()
    {
        $this->expectException(\Exception::class);

        $message = new AMQPMessage();
        $message->body = json_encode(['type' => 'other']);

        /** @noinspection PhpUnhandledExceptionInspection */
        MailMessageBuilder::fromAmqp($message);
    }
}
