<?php

namespace OpgTest\Lpa\Logger\Writer;

use Opg\Lpa\Logger\Writer\Sentry;
use PHPUnit\Framework\TestCase;

class SentryTest extends TestCase
{
    private $sentryUri = 'http://user:password@fake.sentry.uri/path';

    public function testDoWrite()
    {
        $writer = new Sentry($this->sentryUri);

        $writer->write([
            'priorityName' => 'INFO',
            'timestamp' => new \DateTime(),
        ]);

        //  TODO - Nothing to assert?
        $this->assertTrue(true);
    }
}
