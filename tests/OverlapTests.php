<?php
	
use Battis\DataUtilities;
	
class OverlapTests extends PHPUnit_Framework_TestCase {

	public function testOverlapLeftRight() {
		$a = 'abcdef';
		$b = 'defghi';
		
		// expected use case
		$this->assertEquals('def', DataUtilities::overlap($a, $b));
	}

	public function testOverlapRightLeft() {
		$a = 'abcdef';
		$b = 'defghi';
		
		// reversed use case
		$this->assertEquals('def', DataUtilities::overlap($b, $a));
	}

	public function testOverlapRightLeftWithoutSwap() {
		$a = 'abcdef';
		$b = 'defghi';

		// reversed use case, no swapping
		$this->assertEquals('', DataUtilities::overlap($b, $a, false));
	}
	
	public function testInternalOverlap() {
		$a = 'abcdef';
		$b = 'cde';
		
		// internal overlap
		$this->assertEquals('cde', DataUtilities::overlap($a, $b));
	}

	public function testNoOverlap() {
		$a = 'abcdef';
		$c = 'lmnop';
		$d = new DateTime();
		
		// no overlap present
		$this->assertEquals('', DataUtilities::overlap($a, $c));
	}

	public function testNonstring() {
		$a = 'abcdef';
		$d = new DateTime();
		
		// non-string argument
		$this->assertEquals('', DataUtilities::overlap($a, $d));
	}
}