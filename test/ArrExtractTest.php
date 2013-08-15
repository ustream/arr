<?php
/**
 * @author pepov <pepov@ustream.tv>
 */
use Ustream\Arr\Arr;

/**
 * ArrExtractTest
 */
class ArrExtractTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function invalidInput()
	{
		$this->setExpectedException('RuntimeException');
		Arr::extract(null, 'asdf')->getOrThrow(new RuntimeException);
	}

	/**
	 * @test
	 */
	public function extractFirstLevel()
	{
		$this->assertSame(123, Arr::extract(array('asd' => 123), 'asd')->getOrThrow(new RuntimeException));
	}

	/**
	 * @test
	 */
	public function notFoundFirstLevel()
	{
		$this->setExpectedException('RuntimeException');
		Arr::extract(array('asd' => 123), 'asdf')->getOrThrow(new RuntimeException);
	}

	/**
	 * @test
	 */
	public function extractDeeper()
	{
		$this->assertSame(87681, Arr::extract(array(
			'asd' => array(
				'lksd' => array(
					'mmfs9' => 87681
				)
			)
		), 'asd.lksd.mmfs9')->getOrElse(function () { throw new RuntimeException; }));
	}

	/**
	 * @test
	 */
	public function notDeepEnough()
	{
		$this->setExpectedException('RuntimeException');
		Arr::extract(
			array(
				'asd' => array(
					'lksd' => 1
				)
			),'asd.lksd.mmfs9'
		)->getOrThrow(new RuntimeException());
	}

	/**
	 * @test
	 */
	public function invalidKey()
	{
		$this->setExpectedException('RuntimeException');
		$this->assertSame(87681, Arr::extract(array(
			'asd' => array(
				'lksd' => array(
					'mmfs9' => 87681
				)
			)
		), null)->getOrElse(function () { throw new RuntimeException; }));
	}
}

?>