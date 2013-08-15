<?php
/**
 * @author blerou <sulik.szabolcs@ustream.tv>
 */

namespace Ustream\Arr;

use Ustream\Option\None;
use Ustream\Option\Option;
use Ustream\Option\Some;

/**
 * Holds the ListExtractor class
 */
class ListExtractor implements Extractor
{
	/**
	 * @var Extractor
	 */
	private $innerExtractor;

	/**
	 * @var bool
	 */
	private $flat;

	/**
	 * @param Extractor $innerExtractor
	 * @param bool      $flat
	 * @return ListExtractor
	 */
	public function __construct($innerExtractor, $flat=false)
	{
		$this->innerExtractor = $innerExtractor;
		$this->flat = $flat;
	}

	/**
	 * @param array $data
	 * @return Option
	 */
	public function extract($data)
	{
		$result = array();
		foreach ($data as $row) {
			$r = $this->innerExtractor->extract($row)->getOrElse(null);
			if ($r !== null) {
				$result[] = $r;
			}
		}
		return $result ? new Some($this->process(array_values($result))) : None::create();
	}

	private function process($result)
	{
		if ($this->flat && is_array($result[0])) {
			return call_user_func_array('array_merge', $result);
		} else {
			return $result;
		}
	}
}
