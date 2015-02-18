<?php
/**
 * Created by PhpStorm.
 * User: minorgod
 * Date: 2/17/2015
 * Time: 2:41 PM
 */

namespace Flexihash\Scripts;



/**
 * A tag in a block of PHPDoc documentation.
 * @author Paul Annesley
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
class Pda_Docblock_Tag
{
	private $_name;
	private $_value;

	/**
	 * @param string $name The name of the tag
	 * @param string $value The value of the tag
	 */
	public function __construct($name, $value = '')
	{
		$this->_name = $name;
		$this->_value = $value;
	}

	/**
	 * The name of the tag
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * The value of the tag
	 * @return string
	 */
	public function getValue()
	{
		return $this->_value;
	}

}
