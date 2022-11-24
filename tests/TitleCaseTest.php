<?php

namespace Tests;

use Battis\DataUtilities\Text;
use PHPUnit\Framework\TestCase;

class TitleCaseTest extends TestCase
{

    public function testLowerCaseWords()
    {
        $a = 'this is a test of the emergency broadcast system';

        // expected use case
        $this->assertEquals('This is a Test of the Emergency Broadcast System', Text::titleCase($a));
    }

    public function testCamelCaseWords()
    {
        $a = 'Does github end up correct?';

        // expected use case
        $this->assertEquals('Does GitHub End Up Correct?', Text::titleCase($a));
    }

    public function testUnderscores()
    {
        $a = 'explode_this_one!';

        // expected use case
        $this->assertEquals('Explode This One!', Text::titleCase($a));
    }

    public function testParams()
    {
        $a = 'the great-Reginald-perCival-of-hOgWaRts';
        $params = [
            'lowerCaseWords' => ['reginald'],
            'allCapsWords' => ['percival'],
            'camelCaseWords' => ['hogwarts' => 'HogWarts'],
            'spaceEquivalents' => ['-']
        ];

        // expected use classes
        $this->assertEquals('The Great reginald PERCIVAL of HogWarts', Text::titleCase($a, $params));
    }
}
