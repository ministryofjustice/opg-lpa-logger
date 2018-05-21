<?php

namespace OpgTest\Lpa\Logger\Writer;

use Aws\Sns\SnsClient;
use Opg\Lpa\Logger\Writer\Sns;
use PHPUnit\Framework\TestCase;
use Mockery;

class SnsTest extends TestCase
{
    public function testDoWrite()
    {
        $reflectionClass = new \ReflectionClass(Sns::class);

        $writer = new Sns([
            'region' => 'region',
            'version' => 'latest',
        ], [
            2 => 'endpoint',
        ]);

        //  Replace the SNS client with a mocked version via reflection
        $snsClient = Mockery::mock(SnsClient::class);
        $snsClient->shouldReceive('publish')
                  ->with([
                      'TopicArn' => 'endpoint',
                      'Message' => 'Alert message 1',
                      'Subject' => 'OPG LPA Digital Service Alert',
                      'MessageStructure' => 'string',
                      'MessageAttributes' => [
                          'ZendLogLevel' => [
                              'DataType' => 'String',
                              'StringValue' => 'ALERT',
                          ],
                      ],
                  ])
                  ->andReturnNull();

        $reflectionProperty = $reflectionClass->getProperty('snsClient');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($writer, $snsClient);

        $writer->write([
            'priorityName' => 'ALERT',
            'message' => 'Alert message 1',
            'timestamp' => new \DateTime(),
        ]);

        //  TODO - Nothing to assert?
        $this->assertTrue(true);
    }
}
