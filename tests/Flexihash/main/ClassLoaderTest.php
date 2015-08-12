<?php namespace Flexihash\Tests;
use \Flexihash\Exception;
/**
 * @author Paul Annesley
 * @package Flexihash
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
class Flexihash_ClassLoaderTest extends \PHPUnit_Framework_TestCase
{

	public function testClassLoaderLoadsFlexihashClass()
	{
		// this test is fragile when run in a grouptest/testsuite environment
		$this->assertFalse(in_array('Flexihash\Exception', get_declared_classes()),
			'Exception should not be declared yet');

		$e = new Exception;

		$this->assertTrue(in_array('Flexihash\Exception', get_declared_classes()),
			'Exception should be declared after autoload');
	}

}

