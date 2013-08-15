<?php
/**
 * @author blerou <sulik.szabolcs@ustream.tv>
 */
use PHPUnit\Extensions\Mock\MockBuilder;
use Ustream\Arr\CompositeExtractor;
use Ustream\Option\None;
use Ustream\Option\Some;

/**
 * Holds the CompositeExtractorTest class
 */
class CompositeExtractorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function shouldExtractAllFieldPathPairsFromDataMap()
	{
		$inputData = array('foo' => array('bar' => 'baz'));
		$firstResult = array('bar' => 'baz');
		$secondResult = 'baz';

		$firstExtractor = $this->extractor();
		$firstExtractor->expectsMethodOnce('extract')->with($inputData)->willReturn(new Some($firstResult));
		$secondExtractor = $this->extractor();
		$secondExtractor->expectsMethodOnce('extract')->with($firstResult)->willReturn(new Some($secondResult));
		$extractor = new CompositeExtractor($firstExtractor->mock, $secondExtractor->mock);
		$this->assertEquals($secondResult, $extractor->extract($inputData)->getOrElse(null));
	}

	/**
	 * @test
	 */
	public function shouldReturnNoneIfAnyExtractorReturnsNone()
	{
		$firstExtractor = $this->extractor();
		$firstExtractor->expectsAny('extract')->willReturn(None::create());
		$secondExtractor = $this->extractor();
		$secondExtractor->expectNoMethodCalls();
		$extractor = new CompositeExtractor($firstExtractor->mock, $secondExtractor->mock);
		$extractor->extract(array());


		$firstExtractor = $this->extractor();
		$firstExtractor->expectsAny('extract')->willReturn(new Some(array()));
		$secondExtractor = $this->extractor();
		$secondExtractor->expectsAny('extract')->willReturn(None::create());
		$extractor = new CompositeExtractor($firstExtractor->mock, $secondExtractor->mock);
		$extractor->extract(array());
	}

	/**
	 * @return \PHPUnit\Extensions\Mock\MockObjectWrapper
	 */
	private function extractor()
	{
		return MockBuilder::create($this, 'Ustream\Arr\Extractor')->build();
	}
}
