<?php

namespace Nommyde;


interface MailerInterface
{
    public function send(MailMessage $message);
}
