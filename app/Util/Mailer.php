<?php

namespace App\Util;

use PHPMailer\PHPMailer\PHPMailer as _Mailer;
use PHPMailer\PHPMailer\Exception as MailException;

class Mailer extends _Mailer
{
    protected $_config;

    public function __construct ($config)
    {
        parent::__construct();
        $this->_config = $config;

        $this->isSMTP();
        $this->Host = $config->get('mail.host');
        $this->SMTPAuth = $config->get('mail.smtp_auth');
        $this->SMTPSecure = $config->get('mail.smtp_secure');
        $this->Port = $config->get('mail.port');
        $this->Username = $config->get('mail.username');
        $this->Password = $config->get('mail.password');
        $this->isHTML($config->get('mail.html'));

        $this->setFrom($config->get('mail.username'),'Tasks Manager');
    }

    public function readyForSend ($mail,$subject,$body,$altBody='')
    {
        $this->addAddress($mail);
        $this->Subject = $subject;
        $this->Body = $body;
        $this->AltBody = $altBody;
    }

    public function AttemptForSend ()
    {
        try {
            $this->send();
            return true;
        }
        catch (MailException $ex) {
            return false;
        }
    }
}