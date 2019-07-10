<?php

namespace OpgTest\Lpa\Logger;

use Opg\Lpa\Logger\Logger;
use Opg\Lpa\Logger\LoggerTrait;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{

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
