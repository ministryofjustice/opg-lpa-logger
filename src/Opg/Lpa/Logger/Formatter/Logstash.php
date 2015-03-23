<?php
namespace Opg\Lpa\Logger\Formatter;

use Zend\Log\Formatter\FormatterInterface;
use DateTime;

class Logstash implements FormatterInterface
{
    /**
     * Formats data into a single line to be written by the writer.
     *
     * @param array $event event data
     * @return string formatted line to write to the log
     */
    public function format($event)
    {
        if (isset($event['timestamp']) && $event['timestamp'] instanceof DateTime) {
            $event['timestamp'] = $event['timestamp']->format($this->getDateTimeFormat());
        }
    
        if ($this->elementMap === null) {
            $dataToInsert = $event;
        } else {
            $dataToInsert = array();
            foreach ($this->elementMap as $elementName => $fieldKey) {
                $dataToInsert[$elementName] = $event[$fieldKey];
            }
        }
    
        $enc     = $this->getEncoding();
        $escaper = $this->getEscaper();
        
        $logstashArray = [
            '@version' => 1,
            '@timestamp' =>  $event['timestamp'],
            'host' => $_SERVER['HTTP_HOST'],
        ];
        
        foreach ($dataToInsert as $key => $value) {
            if (empty($value)
                || is_scalar($value)
                || (is_object($value) && method_exists($value, '__toString'))
            ) {
                if ($key == "message") {
                    $value = $escaper->escapeHtml($value);
                } elseif ($key == "extra" && empty($value)) {
                    continue;
                }
                $logstashArray[$key] = $value;
            }
        }
    
        $json = json_encode($logstashArray);
            
        return $json . PHP_EOL;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDateTimeFormat()
    {
        return $this->dateTimeFormat;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDateTimeFormat($dateTimeFormat)
    {
        $this->dateTimeFormat = (string) $dateTimeFormat;
        return $this;
    }
}
