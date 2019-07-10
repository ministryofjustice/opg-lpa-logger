<?php

namespace Opg\Lpa\Logger;

use Zend\Log\Logger as ZendLogger;
use Zend\Log\Writer\Stream as StreamWriter;
use Zend\Log\Formatter\Simple as SimpleFormatter;

/**
 * class Logger
 *
 * A simple StreamWriter file logger
 */
class Logger extends ZendLogger
{
    /**
     * @var Logger
     */
    private static $instance = null;

    /**
     * Logger constructor
     *
     */
    public function __construct()
    {
        parent::__construct();

        $writer = new StreamWriter('php://stderr');

        $writer->setFormatter(new SimpleFormatter([
            'format' => SimpleFormatter::DEFAULT_FORMAT,
            'dateTimeFormat' => 'Y-m-d\TH:i:s.u\Z',
        ]));

        $this->addWriter($writer);
    }

    /**
     * Singleton provider for logger
     * Required so logger can be loaded in all services including none ZF2
     *
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Destroy the logger
     */
    public static function destroy()
    {
        self::$instance = null;
    }
}
