<?php

namespace Tests;

use Battis\DataUtilities;
use PHPUnit\Framework\TestCase;

class URLfromPathTests extends TestCase
{
    public function testCLI()
    {
        $path = __FILE__;

        // simulate CLI call with no $_SERVER
        $this->assertEquals(false, DataUtilities::URLfromPath(__FILE__, null));
    }

    public function testServerDocumentRoot()
    {
        $path = __FILE__;
        $server = array(
            'HTTPS' => 'on',
            'SERVER_NAME' => 'data-utilities.phpunit.de',
            'CONTEXT_PREFIX' => '',
            'CONTEXT_DOCUMENT_ROOT' => dirname(dirname(dirname(dirname(__FILE__))))
        );

        $this->assertEquals(
            'https://data-utilities.phpunit.de/battis/data-utilities/tests/URLfromPathTest.php',
            DataUtilities::URLfromPath($path, $server)
        );
    }

    public function testUserWebFolder()
    {
        $path = __FILE__;
        $server = array(
            'HTTPS' => 'on',
            'SERVER_NAME' => 'data-utilities.phpunit.de',
            'CONTEXT_PREFIX' => '/~foo',
            'CONTEXT_DOCUMENT_ROOT' => dirname(dirname(dirname(dirname(__FILE__))))
        );

        $this->assertEquals(
            'https://data-utilities.phpunit.de/~foo/battis/data-utilities/tests/URLfromPathTest.php',
            DataUtilities::URLfromPath($path, $server)
        );
    }
}
