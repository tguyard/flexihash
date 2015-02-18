<?php
/**
 * Created by PhpStorm.
 * User: minorgod
 * Date: 2/17/2015
 * Time: 2:43 PM
 */

namespace Flexihash\Scripts;

/**
 * A block of PHPDoc documentation.
 * @author Paul Annesley
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
class Pda_Docblock
{

	const DOCBLOCK_OPEN  = "/**\n";
	const DOCBLOCK_BODY  = ' * ';
	const DOCBLOCK_CLOSE = " */\n";
	const DOCBLOCK_NEWLINE = "\n";
	const DOCBLOCK_TAGSIGIL = '@';

	private $_shortDescription;
	private $_longDescription;
	private $_tags = array();
	private $_indent = '';

	/**
	 * The short description, up to three lines, terminated by a period.
	 * @param string $shortDescription
	 * @return $this
	 */
	public function setShortDescription($shortDescription)
	{
		$this->_shortDescription = $shortDescription;
		return $this;
	}

	/**
	 * The long description, up to three lines, terminated by a period.
	 * @param string $longDescription
	 * @return $this
	 */
	public function setLongDescription($longDescription)
	{
		$this->_longDescription = $longDescription;
		return $this;
	}

	/**
	 * @param string $name The name of the tag
	 * @param string $value The value of the tag
	 * @return $this
	 */
	public function addTag($name, $value = '')
	{
		$this->_tags []= new Pda_Docblock_Tag($name, $value);
		return $this;
	}

	/**
	 * The indentation to apply when serializing, e.g. "\t\t"
	 * @param string $indent
	 * @return $this
	 */
	public function setIndent($indent)
	{
		$this->_indent = $indent;
		return $this;
	}

	/**
	 * @return string
	 */
	public function serialize()
	{
		$i = $this->_indent;
		$output = $i . self::DOCBLOCK_OPEN;

		if (isset($this->_shortDescription))
		{
			// TODO: handle multi-line short descriptions
			$output .= $i .
				self::DOCBLOCK_BODY .
				$this->_shortDescription .
				self::DOCBLOCK_NEWLINE;
		}

		if (isset($this->_longDescription))
		{
			if (isset($this->_shortDescription))
			{
				// blank line between long & short descriptions
				$output .= $i . self::DOCBLOCK_BODY . self::DOCBLOCK_NEWLINE;
			}

			// TODO: handle wrapping long lines to correct length.
			$output .= $i .
				self::DOCBLOCK_BODY .
				preg_replace(
					'#\n#',
					self::DOCBLOCK_NEWLINE . $i . self::DOCBLOCK_BODY,
					$this->_longDescription
				) .
				self::DOCBLOCK_NEWLINE;
		}

		foreach ($this->_tags as $tag)
		{
			// TODO: handle multi-line tag values
			$output .= $i .
				self::DOCBLOCK_BODY .
				self::DOCBLOCK_TAGSIGIL .
				$tag->getName() .
				' ' .
				$tag->getValue()  .
				self::DOCBLOCK_NEWLINE;
		}

		$output .= $i . self::DOCBLOCK_CLOSE;

		return $output;
	}

	/**
	 * Alias for self::serialize()
	 */
	public function __toString()
	{
		return $this->serialize();
	}

}