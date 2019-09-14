<?php

namespace JorisVaesen\Sendgrid\Mailer\Transport;

use Cake\Mailer\AbstractTransport;
use Cake\Mailer\Email;
use Cake\Utility\Hash;
use SendGrid\Mail\Attachment;

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

        foreach ($email->getAttachments() as $name => $info) {
            $attachment = new Attachment();
            $attachment->setFilename($name);

            if (isset($info['data'])) {
                $attachment->setContent(base64_decode($info['data']));
            }

            if (isset($info['file'])) {
                $attachment->setContent(file_get_contents($info['file']));
            }

            $contentId = Hash::get($info, 'contentId');
            if ($contentId) {
                $attachment->setContentID($contentId);
            }

            $type = Hash::get($info, 'mimetype');
            if ($type) {
                $attachment->setType($type);
            }

            $sendgridEmail->addAttachment($attachment);
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