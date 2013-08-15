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
		$this->setExpectedException('NotFound_Exception');
		Arr::extract(null, 'asdf')->getOrThrow(new NotFound_Exception);
	}

	/**
	 * @test
	 */
	public function extractFirstLevel()
	{
		$this->assertSame(123, Arr::extract(array('asd' => 123), 'asd')->getOrThrow(new NotFound_Exception));
	}

	/**
	 * @test
	 */
	public function notFoundFirstLevel()
	{
		$this->setExpectedException('NotFound_Exception');
		Arr::extract(array('asd' => 123), 'asdf')->getOrThrow(new NotFound_Exception);
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
		), 'asd.lksd.mmfs9')->getOrElse(function () { throw new NotFound_Exception; }));
	}

	/**
	 * @test
	 */
	public function notDeepEnough()
	{
		$this->setExpectedException('NotFound_Exception');
		Arr::extract(
			array(
				'asd' => array(
					'lksd' => 1
				)
			),'asd.lksd.mmfs9'
		)->getOrThrow(new NotFound_Exception());
	}

	/**
	 * @test
	 */
	public function invalidKey()
	{
		$this->setExpectedException('NotFound_Exception');
		$this->assertSame(87681, Arr::extract(array(
			'asd' => array(
				'lksd' => array(
					'mmfs9' => 87681
				)
			)
		), null)->getOrElse(function () { throw new NotFound_Exception; }));
	}
}

?>