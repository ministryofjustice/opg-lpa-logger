<?php
namespace Opg\Lpa\Logger\Writer;

use Zend\Log\Writer\AbstractWriter;
use Aws\Sns\SnsClient;

class Sns extends AbstractWriter
{
    const SNS_MINOR = 0;
    const SNS_MAJOR = 1;
    
    /**
     * Translates Zend Framework log levels to minor or major
     */
    private $logLevels = [
        'DEBUG'     => self::SNS_MINOR,
        'INFO'      => self::SNS_MINOR,
        'NOTICE'    => self::SNS_MINOR,
        'WARN'      => self::SNS_MINOR,
        'ERR'       => self::SNS_MINOR,
        'CRIT'      => self::SNS_MINOR,
        'ALERT'     => self::SNS_MINOR,
        'EMERG'     => self::SNS_MAJOR,
    ];
    
    /**
     * Ignore messages with these levels
     */
    private $ignoreLevels = [
        'DEBUG',
        'INFO',
        'NOTICE',
        'WARN',
        'ERR',
    ];
    
    /**
     * @var Aws\Sns\SnsClient
     */
    private $snsClient;
    
    private $topicArn;
    
    /**
     * Constructor
     *
     * @return array $options
     */
    public function __construct(array $config, $topicArn, $options = null)
    {
        $this->snsClient = new SnsClient($config);
        
        $this->topicArn = $topicArn;
        
        parent::__construct($options);
    }
    
    /**
     * Write a message to the log
     *
     * @param array $event log data event
     * @return number The event ID
     */
    protected function doWrite(array $event)
    {
        $extra = array();
        $extra['timestamp'] = $event['timestamp'];
        
        $zendLogLevel = $event['priorityName'];
        
        if (!in_array($zendLogLevel, $this->ignoreLevels)) {
            $snsLogLevel = $this->logLevels[$zendLogLevel];
            
            $result = $this->snsClient->publish(array(
                'TopicArn' => $this->topicArn,
                // Message is required
                'Message' => $event['message'],
                'Subject' => 'OPG LPA Digital Service Alert',
                'MessageStructure' => 'string',
                'MessageAttributes' => array(
                    // Associative array of custom 'String' key names
                    'ZendLogLevel' => array(
                        // DataType is required
                        'DataType' => 'String',
                        'StringValue' => $zendLogLevel,
                    ),
                ),
            ));

        }
    }
}