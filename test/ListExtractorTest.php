<?php
/**
 * @author blerou <sulik.szabolcs@ustream.tv>
 */
use PHPUnit\Extensions\Mock\MockBuilder;
use Ustream\Arr\Extractor;
use Ustream\Arr\ExtractorUtils as E;
use Ustream\Arr\ListExtractor;
use Ustream\Arr\PathExtractor;

/**
 * Holds the ListExtractorTest class
 */
class ListExtractorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function shouldExtractSimplePathFromDataMap()
	{
		$extractor = new ListExtractor(E::path('baz'));
		$this->assertExtracts($extractor, array('something1', 'something2'));
	}

	/**
	 * @test
	 */
	public function shouldDropUnextractableRowsFromResult()
	{
		$extractor = new ListExtractor(E::path('quuz'));
		$this->assertExtracts($extractor, array('other'));
	}

	/**
	 * @test
	 */
	public function shouldReturnsNoneWhenTheresNothingToExtract()
	{
		$extractor = new ListExtractor(E::path('qwerty'));
		$this->assertThat($extractor->extract(array()), $this->isInstanceOf('Ustream\Option\None'));
	}

	/**
	 * @param Extractor $extractor
	 * @param string    $expected
	 */
	private function assertExtracts($extractor, $expected)
	{
		$sample = array(
			array(
				'foo' => array('bar' => 'result1'),
				'baz' => 'something1',
			),
			array(
				'foo' => array('bar' => 'result2'),
				'baz' => 'something2',
				'quuz' => 'other',
			),
		);
		$this->assertEquals($expected, $extractor->extract($sample)->getOrElse(null));
	}

	/**
	 * @test
	 */
	public function shouldFlatOutResultWhenAsked()
	{
		$inputData = array(
			array(
				array('key' => 'val1'),
				array('key' => 'val2'),
			),
			array(
				array('key' => 'val3'),
				array('key' => 'val4'),
			),
		);
		$expected = array('val1', 'val2', 'val3', 'val4');

		$inner = new ListExtractor(new PathExtractor('key'));
		$extractor = new ListExtractor($inner, true);

		$this->assertEquals($expected, $extractor->extract($inputData)->getOrElse(array()));
	}

	/**
	 * @test
	 */
	public function flatOutAndFilteringWorksTogether()
	{
		$inputData = array(
			array(
				array('foo' => 'bar'),
			),
			array(
				array('key' => 'val1'),
				array('key' => 'val2'),
			),
			array(
				array('key' => 'val3'),
				array('key' => 'val4'),
			),
		);
		$expected = array('val1', 'val2', 'val3', 'val4');

		$inner = new ListExtractor(new PathExtractor('key'));
		$extractor = new ListExtractor($inner, true);

		$this->assertEquals($expected, $extractor->extract($inputData)->getOrElse(array()));
	}
}
