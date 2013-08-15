<?php
/**
 * @author blerou <sulik.szabolcs@ustream.tv>
 */

namespace Ustream\Arr;

use Ustream\Option\Option;

/**
 * Holds the Extractor interface
 */
interface Extractor
{
	/**
	 * @param array $data
	 * @return Option
	 */
	public function extract($data);
}