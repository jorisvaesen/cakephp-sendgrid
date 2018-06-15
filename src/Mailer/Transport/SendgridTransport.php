<?php

namespace JorisVaesen\Sendgrid\Mailer\Transport;

use Cake\Mailer\AbstractTransport;
use Cake\Mailer\Email;

class SendgridTransport extends AbstractTransport
{
    protected function setupSendgrid()
    {
        return new \SendGrid($this->getConfig('password'));
    }

    protected function setupEmail(Email $email)
    {
        $sendgridEmail = new \SendGrid\Mail\Mail();

        foreach ($email->getTo() as $e => $n) {
            $sendgridEmail->addTo($e, $n);
        }

        foreach ($email->getCc() as $e => $n) {
            $sendgridEmail->addCc($e, $n);
        }

        foreach ($email->getBcc() as $e => $n) {
            $sendgridEmail->addBcc($e, $n);
        }

        foreach ($email->getFrom() as $e => $n) {
            $sendgridEmail->setFrom($e, $n);
        }

        foreach ($email->getReplyTo() as $e => $n) {
            $sendgridEmail->setReplyTo($e, $n);
        }

        foreach ($email->getSender() as $e => $n) {
            $sendgridEmail->addHeader('Sender', sprintf('%s <%s>', $n, $e));
        }

        foreach ($email->getAttachments() as $attachment) {
            $sendgridEmail->addAttachment($attachment['file'], $attachment['custom_filename']);
        }

        $sendgridEmail->setSubject($email->getSubject());

        if ($email->getEmailFormat() === 'both' || $email->getEmailFormat() === 'html') {
            $sendgridEmail->addContent('text/html', $email->message(Email::MESSAGE_HTML));
        }

        if ($email->getEmailFormat() === 'both' || $email->getEmailFormat() === 'text') {
            $sendgridEmail->addContent('text/plain', $email->message(Email::MESSAGE_TEXT));
        }

        return $sendgridEmail;
    }

    public function send(Email $email)
    {
        $sendgridEmail = $this->setupEmail($email);

        if (is_callable($this->getConfig('callback'))) {
            $sendgridEmail = call_user_func($this->getConfig('callback'), $sendgridEmail, $email);
        }

        return $this->setupSendgrid()->send($sendgridEmail);
    }
}