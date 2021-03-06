<?php namespace Flexihash\Tests;
use \Flexihash\Flexihash;
use \Flexihash\Hasher;
use \Flexihash\Crc32Hasher;
use \Flexihash\Md5Hasher;
use \Flexihash\Exception;

/**
 * Benchmarks, not really tests.
 *
 * @author Paul Annesley
 * @package Flexihash
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
class Flexihash_BenchmarkTest extends \PHPUnit_Framework_TestCase
{
	private $_targets = 10;
	private $_lookups = 1000;

	public function testAddTargetWithNonConsistentHash()
	{
		$results1 = array();
		foreach (range(1, $this->_lookups) as $i) $results1[$i] = $this->_basicHash("t$i", 10);

		$results2 = array();
		foreach (range(1, $this->_lookups) as $i) $results2[$i] = $this->_basicHash("t$i", 11);

		$differences = 0;
		foreach (range(1, $this->_lookups) as $i) if ($results1[$i] !== $results2[$i]) $differences++;

		$percent = round($differences / $this->_lookups * 100);
		$this->assertEquals(92.0,$percent);

		echo("\nNonConsistentHash: {$percent}% of lookups changed " ."after adding a target to the existing {$this->_targets}\n");
	}

	public function testRemoveTargetWithNonConsistentHash()
	{
		$results1 = array();
		foreach (range(1, $this->_lookups) as $i) $results1[$i] = $this->_basicHash("t$i", 10);

		$results2 = array();
		foreach (range(1, $this->_lookups) as $i) $results2[$i] = $this->_basicHash("t$i", 9);

		$differences = 0;
		foreach (range(1, $this->_lookups) as $i) if ($results1[$i] !== $results2[$i]) $differences++;

		$percent = round($differences / $this->_lookups * 100);

		$this->assertEquals(90.0,$percent);

		echo("\nNonConsistentHash: {$percent}% of lookups changed " ."after removing 1 of {$this->_targets} targets\n");
	}

	public function testHopeAddingTargetDoesNotChangeMuchWithCrc32Hasher()
	{
		$hashSpace = new Flexihash(
			new Crc32Hasher()
		);
		foreach (range(1,$this->_targets) as $i) $hashSpace->addTarget("target$i");

		$results1 = array();
		foreach (range(1, $this->_lookups) as $i) $results1[$i] = $hashSpace->lookup("t$i");

		$hashSpace->addTarget("target-new");

		$results2 = array();
		foreach (range(1, $this->_lookups) as $i) $results2[$i] = $hashSpace->lookup("t$i");

		$differences = 0;
		foreach (range(1, $this->_lookups) as $i) if ($results1[$i] !== $results2[$i]) $differences++;

		$percent = round($differences / $this->_lookups * 100);

		$this->assertEquals(6.0,$percent);
		echo("\nConsistentHash: {$percent}% of lookups changed " ."after adding a target to the existing {$this->_targets}\n");
	}

	public function testHopeRemovingTargetDoesNotChangeMuchWithCrc32Hasher()
	{
		$hashSpace = new Flexihash(
			new Crc32Hasher()
		);
		foreach (range(1,$this->_targets) as $i) $hashSpace->addTarget("target$i");

		$results1 = array();
		foreach (range(1, $this->_lookups) as $i) $results1[$i] = $hashSpace->lookup("t$i");

		$hashSpace->removeTarget("target1");

		$results2 = array();
		foreach (range(1, $this->_lookups) as $i) $results2[$i] = $hashSpace->lookup("t$i");

		$differences = 0;
		foreach (range(1, $this->_lookups) as $i) if ($results1[$i] !== $results2[$i]) $differences++;

		$percent = round($differences / $this->_lookups * 100);

		$this->assertEquals(9.0,$percent);

		echo("\nConsistentHash: {$percent}% of lookups changed " . "after removing 1 of {$this->_targets} targets\n");
	}


	public function testHashDistributionWithCrc32Hasher()
	{
		$hashSpace = new Flexihash(
			new Crc32Hasher()
		);

		foreach (range(1,$this->_targets) as $i) $hashSpace->addTarget("target$i");

		$results = array();
		foreach (range(1, $this->_lookups) as $i) $results[$i] = $hashSpace->lookup("t$i");

		$distribution = array();
		foreach ($hashSpace->getAllTargets() as $target)
		{
			$distribution[$target] = count(array_keys($results, $target));
		}


		echo(sprintf(
			"\nDistribution of %d lookups per target (min/max/median/avg): %d/%d/%d/%d \n",
			$this->_lookups / $this->_targets,
			min($distribution),
			max($distribution),
			round($this->_median($distribution)),
			round(array_sum($distribution) / count($distribution))
		));

	}

	public function testHasherSpeed()
	{
		$hashCount = 100000;

		$md5Hasher = new Md5Hasher();
		$crc32Hasher = new Crc32Hasher();

		$start = microtime(true);
		for ($i = 0; $i < $hashCount; $i++)
			$md5Hasher->hash("test$i");
		$timeMd5 = microtime(true) - $start;

		$start = microtime(true);
		for ($i = 0; $i < $hashCount; $i++)
			$crc32Hasher->hash("test$i");
		$timeCrc32 = microtime(true) - $start;


		echo(sprintf(
			"\nHashers timed over %d hashes (MD5 / CRC32): %f / %f \n",
			$hashCount,
			$timeMd5,
			$timeCrc32
		));
	}

	// ----------------------------------------

	private function _basicHash($value, $targets)
	{
		return abs(crc32($value) % $targets);
	}

	/**
	 * @param array $array list of numeric values
	 * @return numeric
	 */
	private function _median($values)
	{
		$values = array_values($values);
		sort($values);

		$count = count($values);
		$middleFloor = floor($count / 2);

		if ($count % 2 == 1)
		{
			return $values[$middleFloor];
		}
		else
		{
			return ($values[$middleFloor] + $values[$middleFloor + 1]) / 2;
		}
	}

}

