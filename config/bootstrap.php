<?php

    use Cake\Core\Configure;

    if (!Configure::check('EmailTransport.sendgrid')) {
        Configure::write('EmailTransport.sendgrid', [
            'className' => '\JorisVaesen\Sendgrid\Mailer\Transport\SendgridTransport',
            'password' => env('SENDGRID_API_KEY', null),
        ]);
    }