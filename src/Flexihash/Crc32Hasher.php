<?php namespace Flexihash;

/**
 * Uses CRC32 to hash a value into a signed 32bit int address space.
 * Under 32bit PHP this (safely) overflows into negatives ints.
 *
 * @author Paul Annesley
 * @package Flexihash
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
class Crc32Hasher
	implements Hasher
{

	/* (non-phpdoc)
	 * @see Hasher::hash()
	 */
	public function hash($string)
	{
		return crc32($string);
	}

}

