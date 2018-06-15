<?php

    use Cake\Mailer\Email;

    Email::setConfigTransport('sendgrid', [
        'className' => '\JorisVaesen\Sendgrid\Mailer\Transport\SendgridTransport',
        'password' => env('SENDGRID_API_KEY', null),
    ]);
