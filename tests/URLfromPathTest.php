<?php

namespace Tests;

use Battis\DataUtilities\URL;
use PHPUnit\Framework\TestCase;

class URLfromPathTests extends TestCase
{
    public function testCLI()
    {
        // simulate CLI call with no $_SERVER
        $this->assertEquals(false, URL::fromPath(__FILE__, null));
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
            URL::fromPath($path, $server)
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
            URL::fromPath($path, $server)
        );
    }

    public function testBasePath()
    {
        $path = '../tests/' . basename(__FILE__);
        $server = array(
            'HTTPS' => 'on',
            'SERVER_NAME' => 'data-utilities.phpunit.de',
            'CONTEXT_PREFIX' => '',
            'CONTEXT_DOCUMENT_ROOT' => dirname(dirname(dirname(dirname(__FILE__))))
        );
        $basePath = dirname(__DIR__) . '/src/';

        $this->assertEquals(
            'https://data-utilities.phpunit.de/battis/data-utilities/tests/URLfromPathTest.php',
            URL::fromPath($path, $server, $basePath)
        );
    }
}
