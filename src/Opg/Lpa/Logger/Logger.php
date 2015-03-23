<?php
namespace Opg\Lpa\Logger;

use Zend\Log\Logger as ZendLogger;
use Zend\Log\Writer\Stream;
use Opg\Lpa\Logger\Formatter\Logstash;

/**
 * class Logger
 * 
 * A simple logstash file logger
 */
class Logger extends ZendLogger 
{
    public function __construct($logFilename)
    {
        parent::__construct();
        
        $logWriter = new Stream($logFilename);
        $formatter = new Logstash();
        
        $logWriter->setFormatter($formatter);
        
        $this->addWriter($logWriter);
    }
}
