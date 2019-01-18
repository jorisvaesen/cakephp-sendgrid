<?php

    use Cake\Mailer\TransportFactory;

    if (!TransportFactory::getConfig('sendgrid')) {
        TransportFactory::setConfig('sendgrid', [
            'className' => '\JorisVaesen\Sendgrid\Mailer\Transport\SendgridTransport',
            'password' => env('SENDGRID_API_KEY', null),
        ]);
    }
