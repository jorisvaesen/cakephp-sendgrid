<?php

    use Cake\Mailer\Email;

    if (!Email::getConfigTransport('sendgrid')) {
        Email::setConfigTransport('sendgrid', [
            'className' => '\JorisVaesen\Sendgrid\Mailer\Transport\SendgridTransport',
            'password' => env('SENDGRID_API_KEY', null),
        ]);
    }
