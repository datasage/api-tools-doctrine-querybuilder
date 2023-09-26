<?php

declare(strict_types=1);

namespace LaminasTest\ApiTools\Doctrine\QueryBuilder\Filter;

use DateTime;
use DateTimeImmutable;
use Laminas\ApiTools\Doctrine\QueryBuilder\Filter\ODM\TypeCaster;
use PHPUnit\Framework\TestCase;
use stdClass;

class ODMTypeCasterTest extends TestCase
{
    /** @var TypeCaster */
    private $typeCaster;

    protected function setUp(): void
    {
        $this->typeCaster = new TypeCaster();
    }

    public function testTypeCastingToInteger(): void
    {
        $metadata                = new stdClass();
        $metadata->fieldMappings = [
            'field' => [
                'type' => 'int',
            ],
        ];

        $field                 = 'field';
        $value                 = '2144211244';
        $format                = null;
        $doNotTypecastDateTime = false;

        $result = $this->typeCaster->typeCastField($metadata, $field, $value, $format, $doNotTypecastDateTime);
        $this->assertSame(2144211244, $result);
    }

    public function testTypeCastingToBoolean(): void
    {
        $metadata                = new stdClass();
        $metadata->fieldMappings = [
            'field' => [
                'type' => 'boolean',
            ],
        ];

        $field                 = 'field';
        $value                 = 'abc';
        $format                = null;
        $doNotTypecastDateTime = false;

        $result = $this->typeCaster->typeCastField($metadata, $field, $value, $format, $doNotTypecastDateTime);
        $this->assertSame(true, $result);

        $value  = 0;
        $result = $this->typeCaster->typeCastField($metadata, $field, $value, $format, $doNotTypecastDateTime);
        $this->assertSame(false, $result);
    }

    public function testTypeCastingToFloat(): void
    {
        $metadata                = new stdClass();
        $metadata->fieldMappings = [
            'field' => [
                'type' => 'float',
            ],
        ];

        $field                 = 'field';
        $value                 = '1.242';
        $format                = null;
        $doNotTypecastDateTime = false;

        $result = $this->typeCaster->typeCastField($metadata, $field, $value, $format, $doNotTypecastDateTime);
        $this->assertSame(1.242, $result);
    }

    public function testTypeCastingToString(): void
    {
        $metadata                = new stdClass();
        $metadata->fieldMappings = [
            'field' => [
                'type' => 'string',
            ],
        ];

        $field                 = 'field';
        $value                 = 1;
        $format                = null;
        $doNotTypecastDateTime = false;

        $result = $this->typeCaster->typeCastField($metadata, $field, $value, $format, $doNotTypecastDateTime);

        $this->assertSame('1', $result);
    }

    public function testTypeCastingToDate(): void
    {
        $metadata                = new stdClass();
        $metadata->fieldMappings = [
            'field' => [
                'type' => 'date',
            ],
        ];

        $field                 = 'field';
        $value                 = '2019-09-01 12:19:01';
        $format                = null;
        $doNotTypecastDateTime = false;

        $result = $this->typeCaster->typeCastField($metadata, $field, $value, $format, $doNotTypecastDateTime);
        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertEquals($result->format('Y-m-d H:i:s'), '2019-09-01 12:19:01');
    }

    public function testNoTypeCastingToDateWhenFlaggedSo(): void
    {
        $metadata                = new stdClass();
        $metadata->fieldMappings = [
            'field' => [
                'type' => 'date',
            ],
        ];

        $field                 = 'field';
        $value                 = '2019-09-01 12:19:01';
        $format                = null;
        $doNotTypecastDateTime = true;

        $result = $this->typeCaster->typeCastField($metadata, $field, $value, $format, $doNotTypecastDateTime);
        $this->assertSame($result, $value);
    }

    public function testTypeCastingToDateImmutable(): void
    {
        $metadata                = new stdClass();
        $metadata->fieldMappings = [
            'field' => [
                'type' => 'date_immutable',
            ],
        ];

        $field                 = 'field';
        $value                 = '2019-09-01 12:19:01';
        $format                = null;
        $doNotTypecastDateTime = false;

        $result = $this->typeCaster->typeCastField($metadata, $field, $value, $format, $doNotTypecastDateTime);
        $this->assertInstanceOf(DateTimeImmutable::class, $result);
        $this->assertEquals($result->format('Y-m-d H:i:s'), '2019-09-01 12:19:01');
    }

    public function testNoTypeCastingToDateImmutableWhenFlaggedSo(): void
    {
        $metadata                = new stdClass();
        $metadata->fieldMappings = [
            'field' => [
                'type' => 'date_immutable',
            ],
        ];

        $field                 = 'field';
        $value                 = '2019-09-01 12:19:01';
        $format                = null;
        $doNotTypecastDateTime = true;

        $result = $this->typeCaster->typeCastField($metadata, $field, $value, $format, $doNotTypecastDateTime);
        $this->assertSame($result, $value);
    }
}
