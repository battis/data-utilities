<?php

namespace Tests;

use Battis\DataUtilities\Text;
use PHPUnit\Framework\TestCase;
use DateTime;

class OverlapTests extends TestCase
{

    public function testOverlapLeftRight()
    {
        $a = 'abcdef';
        $b = 'defghi';

        // expected use case
        $this->assertEquals('def', Text::overlap($a, $b));
    }

    public function testOverlapRightLeft()
    {
        $a = 'abcdef';
        $b = 'defghi';

        // reversed use case
        $this->assertEquals('def', Text::overlap($b, $a));
    }

    public function testOverlapRightLeftWithoutSwap()
    {
        $a = 'abcdef';
        $b = 'defghi';

        // reversed use case, no swapping
        $this->assertEquals('', Text::overlap($b, $a, false));
    }

    public function testInternalOverlap()
    {
        $a = 'abcdef';
        $b = 'cde';

        // internal overlap
        $this->assertEquals('cde', Text::overlap($a, $b));
    }

    public function testNoOverlap()
    {
        $a = 'abcdef';
        $c = 'lmnop';
        $d = new DateTime();

        // no overlap present
        $this->assertEquals('', Text::overlap($a, $c));
    }

    public function testNonstring()
    {
        $a = 'abcdef';
        $d = new DateTime();

        // non-string argument
        $this->assertEquals('', Text::overlap($a, $d));
    }
}
