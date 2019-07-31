<?php

namespace arajcany\Test\Configuration;

use arajcany\FreeFlowCoreAssistant\Configuration\FreeFlowCoreConfig5;
use PHPUnit\Framework\TestCase;

class FreeFlowCoreConfig5Test extends TestCase
{
    private $ffc;

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        include_once(__DIR__ . "/../bootstrap.php");
        $this->ffc = new FreeFlowCoreConfig5();

    }

    public function testGetTenants()
    {
        $expected = [
            '00000000-0000-0000-0000-000000000000' => 'K:\\Xerox\\FreeFlow\\Core\\00000000-0000-0000-0000-000000000000\\'
        ];
        $actual = $this->ffc->getTenants();

        $this->assertEquals($expected, $actual);
    }

    public function testGetFileExtensions()
    {
        $needles = [
            'pdf',
            'ps',
            'csv',
            'jpg',
            'tif',
            'png',
            'doc',
            'ppt',
        ];
        $haystack = $this->ffc->getFileExtensions();

        foreach ($needles as $needle) {
            $this->assertContains($needle, $haystack);
        }
    }

    public function testReadTenantLicenseFile()
    {
        $actual = $this->ffc->readTenantLicenseFile();

        $this->assertArrayHasKey(0, $actual);
    }


}