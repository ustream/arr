<?php
/**
 * @author blerou <sulik.szabolcs@ustream.tv>
 */

namespace Ustream\Arr;

use Ustream\Option\Option;

/**
 * Holds the PathExtractor class
 */
class PathExtractor implements Extractor
{
	/**
	 * @var array|string
	 */
	private $path;

	/**
	 * @param string|array $path
	 */
	public function __construct($path)
	{
		$this->path = $path;
	}

	/**
	 * @param array $data
	 * @return Option
	 */
	public function extract($data)
	{
		return Arr::extract($data, $this->path);
	}
}