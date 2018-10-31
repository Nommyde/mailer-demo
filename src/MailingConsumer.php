<?php

namespace Nommyde;


use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class MailingConsumer implements AmqpConsumerInterface
{
    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger = null)
    {
        $this->mailer = $mailer;
        $this->logger = $logger ?? new NullLogger();
    }

    public function consume(AMQPMessage $message)
    {
        $this->logger->info('message received');

        try {
            $mailMsg = MailMessageBuilder::fromAmqp($message);
            $this->mailer->send($mailMsg);
            $this->logger->info('email sent');
        } catch (\Throwable $e) {
            $this->logger->warning('Exception: ' . $e->getMessage());
        }
    }
}
