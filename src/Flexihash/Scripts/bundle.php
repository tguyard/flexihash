<?php namespace Flexihash\Scripts;
/**
 * Flexihash bundler.
 * Bundles code required to use flexihash into a single PHP file.
 *
 * @author Paul Annesley
 * @package Flexihash
 * @licence http://www.opensource.org/licenses/mit-license.php
 */

use Flexihash\Scripts\Pda_Docblock;

error_reporting(E_ALL);
ini_set('display_errors', true);


// ----------------------------------------

// declaration-level dependencies first
$classFiles = array(
	'classes/Flexihash.php',
	'classes/Flexihash/Hasher.php',
	'classes/Flexihash/Crc32Hasher.php',
	'classes/Flexihash/Md5Hasher.php',
	'classes/Flexihash/Exception.php',
);

$baseDir = realpath(dirname(__FILE__).'/..');
$classDir = "$baseDir/classes";
$licenceFile = "$baseDir/LICENCE";
$buildDir = "$baseDir/build";
$outFile = "$buildDir/flexihash.php";

// ----------------------------------------
// set up build environment

if (is_dir($buildDir))
{
	flexihash_build_log("Build directory exists: $buildDir");
}
else
{
	flexihash_build_log("Creating build directory: $buildDir");
	mkdir($buildDir);
}

// ----------------------------------------
// open bundle file, write header

if (!$fpOut = fopen($outFile, 'w'))
	throw new Exception("Unable to open file for writing: $outFile");

flexihash_build_log("Writing header to $outFile");

// Open PHP tag
fwrite($fpOut, "<?php\n");

// Main file docblock
$docBlock = new Pda_Docblock();
$docBlock
	->setShortDescription('Flexihash - A simple consistent hashing implementation for PHP.')
	->setLongDescription(trim(file_get_contents($licenceFile))."\n")
	->addTag('author', 'Paul Annesley')
	->addTag('link', 'http://paul.annesley.cc/')
	->addTag('copyright', 'Paul Annesley, 2008')
	;

fwrite($fpOut, $docBlock);

// counters
$countFiles = 0;

foreach ($classFiles as $classFile)
{
	$countFiles++;
	flexihash_build_log("Adding $classFile...");

	// open file, discard first line - PHP open tag
	$fpIn = fopen($classFile, 'r');
	fgets($fpIn);
	while (!feof($fpIn)) fwrite($fpOut, fgets($fpIn));
	fclose($fpIn);
}

$pos = ftell($fpOut);
fclose($fpOut);

flexihash_build_log("Bundled $pos bytes from $countFiles files into $outFile");


// ----------------------------------------

/**
 * Logs a message to the console.
 * @param string $message
 */
function flexihash_build_log($message)
{
	printf("%s\n", $message);
}

// ----------------------------------------
// docblock helpers.
// should probably move to a separate library.



