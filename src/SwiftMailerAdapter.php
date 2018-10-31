<?php

namespace Nommyde;


class SwiftMailerAdapter implements MailerInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Transport $transport)
    {
        $this->mailer = new \Swift_Mailer($transport);
    }

    public function send(MailMessage $message)
    {
        $swiftMessage = (new \Swift_Message)
            ->setSubject($message->subject)
            ->setBody($message->body)
            ->setFrom($message->from)
            ->setTo($message->to);

        $this->mailer->send($swiftMessage);
    }
}
