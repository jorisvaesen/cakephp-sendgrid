# Sendgrid wrapper for CakePHP


## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require jorisvaesen/cakephp-sendgrid
```

Load the plugin in __config/bootstrap.php__:
```
Plugin::load('JorisVaesen/Sendgrid', ['bootstrap' => true]);
```

## Configuration

### Method 1 (using ENV)

The plugin loads from dotenv the following:
```
SENDGRID_API_KEY="your_sendgrid_api_key"
```

### Method 2 (using app.php)

Set the sendgrid api key in app.php:
```
'Sendgrid' => [
    'apiKey' => 'your_sendgrid_api_key',
],
```

### Method 3 (hardcoded)

Add in your __app.php__ file, in the __EmailTransport__ item

```
'EmailTransport' => [
        ...
        'sendgrid' => [
            'className' => '\JorisVaesen\Sendgrid\Mailer\Transport\SendgridTransport',
            'password' => 'your_sendgrid_api_key',
        ],
        ...
    ],
```

To use it by default, set your default transport to `sendgrid` in __app.php__:
```
'Email' => [
    'default' => [
        'transport' => 'sendgrid',
    ],
],
```

### Callback

If you want to use Sendgrid API methods which do not have a CakePHP equivalent, you can make use of the callback configuration:
```
'EmailTransport' => [
        ...
        'sendgrid' => [
            'className' => '\JorisVaesen\Sendgrid\Mailer\Transport\SendgridTransport',
            'password' => 'your_sendgrid_api_key',
            'callback' => function (\SendGrid\Mail\Mail $sendgridEmail, \Cake\Mailer\Email $cakephpEmail) {
                if ($cakephpEmail->getSubject() === 'If you open this mail...') {
                    $sendgridEmail->setOpenTracking(true);
                }
                
                return $sendgridEmail;
            },
        ],
        ...
    ],
```

## Usage

```
$email = new Email('default');
$email->viewBuilder()->setLayout('default);
/* @var \Sendgrid\Response $response */
$response = $email
    ->setProfile('sendgrid')    // optional when you've set sendgrid as the default transport in configuration
    ->setEmailFormat('html')
    ->setSender('noreply@example.com', 'Example sender')
    ->setFrom('from@example.com', 'Example From')
    ->setReplyTo('replyTo@example.com', 'Example ReplyTo')
    ->setTo('to@example.com', 'Example To')
    ->setSubject('Sample email')
    ->setTemplate('default')
    ->setViewVars([])
    ->send();
    
return $response->statusCode() === 202;
```

[www.datrix.be](https://www.datrix.be)
