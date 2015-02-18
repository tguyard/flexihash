<?php
/**
 * Created by PhpStorm.
 * User: minorgod
 * Date: 2/17/2015
 * Time: 7:19 PM
 */

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/**
 * @param mixed $items Path or paths as string or array
 */
function flexihash_unshift_include_path($items)
{
	$elements = explode(PATH_SEPARATOR, get_include_path());
	if (is_array($items))
	{
		set_include_path(implode(PATH_SEPARATOR, array_merge($items, $elements)));
	}
	else
	{
		array_unshift($elements, $items);
		set_include_path(implode(PATH_SEPARATOR, $elements));
	}
}
/**
 * SPL autoload function, loads a flexihash class file based on the class name.
 *
 * @param string
 */
function flexihash_autoload($className)
{
	if (preg_match('#^Flexihash#', $className))
	{
		require_once(preg_replace('#_#', '/', $className).'.php');
	}
}
$basedir = realpath(dirname(__FILE__).'/..');
flexihash_unshift_include_path(array("$basedir/src","$basedir/src/Flexihash"));
spl_autoload_register('flexihash_autoload');

