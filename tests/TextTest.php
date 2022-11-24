<?php

namespace Tests;

use Battis\DataUtilities\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    public function testSnake_case_to_PascalCase()
    {
        foreach (
            [
                "snake_case" => "SnakeCase",
                "snake" => "Snake",
                "SNAKE" => "SNAKE",
                "a_b_c_d" => "ABCD",
                "aa_bb_cc_dd" => "AaBbCcDd",
                "foo_123" => "Foo123",
                "123_bar" => "123Bar",
                "foo_123_bar" => "Foo123Bar",
                "" => "",
                "a_b__c___d" => "ABCD",
                "_a_b_c_d" => "ABCD",
                "camelCase" => "CamelCase",
            ]
            as $arg => $expected
        ) {
            $this->assertEquals(
                $expected,
                Text::snake_case_to_PascalCase($arg)
            );
        }
    }

    public function testCamelCase_to_snake_case()
    {
        foreach (
            [
                "camelCase" => "camel_case",
                "camel" => "camel",
                "Camel" => "camel",
                "CAMEL" => "camel",
                "PascalCase" => "pascal_case",
                "Foo123" => "foo_123",
                "123Bar" => "123_bar",
                "foo123Bar" => "foo_123_bar",
                "FOOBar" => "foo_bar",
                "fooBAR" => "foo_bar",
                "fooBARBaz" => "foo_bar_baz",
                "" => "",
            ]
            as $arg => $expected
        ) {
            $this->assertEquals($expected, Text::camelCase_to_snake_case($arg));
        }
    }

    public function testPluralize()
    {
        foreach (
            [
                "test" => "tests",
                "TEST" => "TESTS",
                "tesT" => "tesTS",
                "Test" => "Tests",
                "bus" => "buses",
                "BUS" => "BUSES",
                "Bus" => "Buses",
                "buS" => "buSES",
                "dummy" => "dummies",
                "Dummy" => "Dummies",
                "DUMMY" => "DUMMIES",
                "dummY" => "dummIES",
                "dish" => "dishes",
                "DISH" => "DISHES",
                "disH" => "disHES",
                "DISh" => "DIShes",
                "finch" => "finches",
                "FINCH" => "FINCHES",
                "fincH" => "fincHES",
                "FINCh" => "FINChes",
                "ritz" => "ritzes",
                "RITZ" => "RITZES",
                "ritZ" => "ritZES",
                "RITz" => "RITzes",
            ]
            as $arg => $expected
        ) {
            $this->assertEquals($expected, Text::pluralize($arg));
        }
    }
}