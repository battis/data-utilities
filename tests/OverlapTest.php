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

        // no overlap present
        $this->assertEquals('', Text::overlap($a, $c));
    }
}
