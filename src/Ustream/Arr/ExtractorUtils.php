<?php
/**
 * @author blerou <sulik.szabolcs@ustream.tv>
 */

namespace Ustream\Arr;

/**
 * Holds the ExtractorUtils class
 */
class ExtractorUtils
{
	/**
	 * @param Extractor $e1
	 * @param Extractor $e2
	 * @return CompositeExtractor
	 */
	public static function combine($e1, $e2)
	{
		return new CompositeExtractor($e1, $e2);
	}

	/**
	 * @param string $part1
	 * @param string $partn
	 * @return PathExtractor
	 */
	public static function path()
	{
		return new PathExtractor(func_get_args());
	}

	/**
	 * @param Extractor $inner
	 * @return ListExtractor
	 */
	public static function fromList($inner)
	{
		return new ListExtractor($inner);
	}

	/**
	 * @param Extractor $inner
	 * @return ListExtractor
	 */
	public static function flatList($inner)
	{
		return new ListExtractor($inner, true);
	}

	/**
	 * @param Extractor[] $pathMap
	 * @return MultiPathExtractor
	 */
	public static function multiPath($pathMap)
	{
		return new MultiPathExtractor($pathMap);
	}
}