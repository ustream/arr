<?php
/**
 * This file is part of the Arr package.
 *
 * @copyright Ustream Inc.
 * @author blerou <sulik.szabolcs@ustream.tv>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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