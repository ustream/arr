<?php
/**
 * @author blerou <sulik.szabolcs@ustream.tv>
 */
use Ustream\Arr\Extractor;
use Ustream\Arr\PathExtractor;

/**
 * Holds the PathExtractorTest class
 */
class PathExtractorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function shouldExtractSimplePathFromDataMap()
	{
		$extractor = new PathExtractor('baz');
		$this->assertExtracts($extractor, 'something');
	}

	/**
	 * @test
	 */
	public function shouldExtractMultiPartPathFromDataMap()
	{
		$extractor = new PathExtractor(array('foo', 'bar'));
		$this->assertExtracts($extractor, 'result');
	}

	/**
	 * @param Extractor $extractor
	 * @param string    $expected
	 */
	private function assertExtracts($extractor, $expected)
	{
		$sample = array(
			'foo' => array('bar' => 'result'),
			'baz' => 'something',
		);
		$this->assertEquals($expected, $extractor->extract($sample)->getOrElse(null));
	}
}
