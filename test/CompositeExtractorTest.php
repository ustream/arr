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

use Ustream\Arr\CompositeExtractor;
use Ustream\Arr\Extractor;
use Ustream\Option\None;
use Ustream\Option\Option;
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

		$firstExtractor = new TestExtractor($inputData, new Some($firstResult));
		$secondExtractor = new TestExtractor($firstResult, new Some($secondResult));
		$extractor = new CompositeExtractor($firstExtractor, $secondExtractor);

		$this->assertEquals($secondResult, $extractor->extract($inputData)->getOrElse(null));
		$firstExtractor->assertCalled();
		$secondExtractor->assertCalled();
	}

	/**
	 * @test
	 */
	public function shouldReturnNoneIfAnyExtractorReturnsNone()
	{
		$firstExtractor = new TestExtractor($this->anything(), None::create());
		$secondExtractor = new TestExtractor($this->anything(), null);
		$extractor = new CompositeExtractor($firstExtractor, $secondExtractor);

		$this->assertEquals(None::create(), $extractor->extract(array()));
		$firstExtractor->assertCalled();
		$secondExtractor->assertNotCalled();

		$firstExtractor = new TestExtractor($this->anything(), new Some(array()));
		$secondExtractor = new TestExtractor($this->anything(), None::create());
		$extractor = new CompositeExtractor($firstExtractor, $secondExtractor);

		$this->assertEquals(None::create(), $extractor->extract(array()));
		$firstExtractor->assertCalled();
		$secondExtractor->assertCalled();
	}
}

class TestExtractor implements Extractor
{
	private $input, $output;
	private $called = false;

	/**
	 * @param mixed $input
	 * @param mixed $output
	 */
	public function __construct($input, $output)
	{
		$this->input = $input;
		$this->output = $output;
	}

	/**
	 * @param array $data
	 * @return Option
	 */
	public function extract($data)
	{
		$expected = $this->input instanceof PHPUnit_Framework_Constraint
			? $this->input
			: new PHPUnit_Framework_Constraint_IsEqual($this->input);
		PHPUnit_Framework_Assert::assertThat($data, $expected);
		$this->called = true;
		return $this->output;
	}

	public function assertCalled()
	{
		PHPUnit_Framework_Assert::assertTrue($this->called);
	}

	public function assertNotCalled()
	{
		PHPUnit_Framework_Assert::assertFalse($this->called);
	}
}