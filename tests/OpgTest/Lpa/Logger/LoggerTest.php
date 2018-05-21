<?php

namespace OpgTest\Lpa\Logger;

use Opg\Lpa\Logger\Logger;
use Opg\Lpa\Logger\LoggerTrait;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    private $fileLogPath = '/tmp/testlog.log';

    private $sentryUri = 'http://user:password@fake.sentry.uri/path';

    /**
     * @var Logger
     */
    private $logger;

    public function setUp()
    {
        putenv('OPG_LPA_COMMON_APPLICATION_LOG_PATH=' . $this->fileLogPath);
        putenv('OPG_LPA_COMMON_SENTRY_API_URI=' . $this->sentryUri);
    }

    public function testGetInstanceAndDestroy()
    {
        $logger1 = Logger::getInstance();
        $logger2 = Logger::getInstance();

        $this->assertTrue($logger1 === $logger2);

        Logger::destroy();

        $logger3 = Logger::getInstance();

        $this->assertFalse($logger1 === $logger3);
        $this->assertFalse($logger2 === $logger3);
    }

    public function testSetSnsCredentials()
    {
        $logger = Logger::getInstance();

        $logger->setSnsCredentials([
            'region' => 'region',
            'version' => 'latest',
        ], []);

        //  Nothing really to assert at this point
        $this->assertInstanceOf(Logger::class, $logger);

        Logger::destroy();
    }

    public function testInfo()
    {
        $logger = Logger::getInstance();

        $logger->alert('Alert');

        //  Retrieve the line from the log file
        $line = fgets(fopen($this->fileLogPath, 'r'));

        if ($line !== false) {
            $line = preg_replace('/\r|\n/', '', $line);
        }

        $this->assertContains('"priority":1,"priorityName":"ALERT","message":"Alert"', $line);

        Logger::destroy();
    }

    public function testTrait()
    {
        $logger = Logger::getInstance();

        $traitUser = new LoggerTraitUser();

        $this->assertInstanceOf(Logger::class, $traitUser->getLoggerExt());

        $traitUser->setLogger($logger);

        $this->assertTrue($logger === $traitUser->getLoggerExt());
    }

    public function tearDown()
    {
        unlink($this->fileLogPath);
        Logger::destroy();
    }
}

class LoggerTraitUser
{
    use LoggerTrait;

    //  Proxy the get logger call to the protected function
    public function getLoggerExt()
    {
        return $this->getLogger();
    }
}
