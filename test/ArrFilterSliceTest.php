<?php
/**
 * @author blerou <sulik.szabolcs@ustream.tv>
 */
use Ustream\Arr\Arr;

/**
 * ArrFilterSliceTest
 */
class ArrFilterSliceTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function whenExactParamsGivenThenWorksProperly()
	{
		$ary = range(1, 10);
		$filter = function ($n) { return $n > 3; };
		$offset = 0;
		$length = 3;

		$result = Arr::filterSlice($ary, $filter, $offset, $length);
		$expected = array(4,5,6);

		$this->assertThat($result, $this->isInstanceOf('Ustream\Option\Some'));
		$this->assertEquals($expected, $result->getOrElse(array()));
	}

	/**
	 * @test
	 */
	public function whenLengthIsNullThenWorksProperly()
	{
		$ary = range(1, 10);
		$filter = function ($n) { return $n > 3; };
		$offset = 0;

		$result = Arr::filterSlice($ary, $filter, $offset);
		$expected = array(4,5,6,7,8,9,10);

		$this->assertEquals($expected, $result->getOrElse(array()));
	}

	/**
	 * @test
	 */
	public function whenOffsetGivenThenWorksProperly()
	{
		$ary = range(1, 20);
		$filter = function ($n) { return $n % 2 == 0; };
		$offset = 3;
		$length = 3;

		$result = Arr::filterSlice($ary, $filter, $offset, $length);
		$expected = array(8,10,12);

		$this->assertEquals($expected, $result->getOrElse(array()));
	}

	/**
	 * @test
	 */
	public function whenNoResultFoundReturnsANone()
	{
		$ary = range(1, 10);
		$filter = function ($n) { return $n > 30; };

		$result = Arr::filterSlice($ary, $filter);
		$expected = 'this-is-the-expected';

		$this->assertThat($result, $this->isInstanceOf('Ustream\Option\None'));
		$this->assertEquals($expected, $result->getOrElse($expected));
	}
}
?>