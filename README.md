# OPG LPA Logger

## SNS Example

    $logger = new Logger();
    
    $logger->setSnsCredentials(
        'arn:aws:sns:eu-west-1:923426666275:EXAMPLE',
        [
            'credentials' => [
                    'key' => '',
                    'secret' => '',
            ],
            'version' => '2010-03-31',
            'region' => 'eu-west-1',
        ]
    );
    
    $logger->alert($message1);
    $logger->err($message2);
    $logger->warn($message3);
