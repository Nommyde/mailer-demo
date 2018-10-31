<?php

namespace Nommyde;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MailingCommand extends Command
{
    private $consumer;
    private $queueName;

    public function __construct(AmqpConsumerInterface $consumer, string $queueName, string $commandName)
    {
        parent::__construct($commandName);
        $this->consumer = $consumer;
        $this->queueName = $queueName;
    }

    public function configure()
    {
        $this
            ->addOption('host', null, InputOption::VALUE_REQUIRED, 'AMQP host', 'localhost')
            ->addOption('port', 'p', InputOption::VALUE_REQUIRED, 'AMQP port', 5672)
            ->addOption('user', 'u', InputOption::VALUE_REQUIRED, 'AMQP user', 'guest')
            ->addOption('pass', null, InputOption::VALUE_REQUIRED, 'AMQP password', 'guest')
            ->setDescription('Main consuming task');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \ErrorException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = new AMQPStreamConnection(
            $input->getOption('host'),
            $input->getOption('port'),
            $input->getOption('user'),
            $input->getOption('pass')
        );

        $channel = $connection->channel();
        $channel->queue_declare($this->queueName, false, false, false, false);

        $output->writeln('Waiting for messages...');

        $channel->basic_consume($this->queueName, '', false, true, false, false, [$this->consumer, 'consume']);

        while (true) {
            $channel->wait();
        }
    }
}
