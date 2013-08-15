<?php
/**
 * @author pepov <pepov@ustream.tv>
 */

use Ustream\Arr\Arr;
use Ustream\Option\Some;
use Ustream\Option\None;

/**
 * ArrMapTest
 */
class ArrMapTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 *
	 * @param mixed $empty
	 *
	 * @dataProvider emptyOrInvalidValues
	 *
	 * @throws Exception
	 * @return void
	 */
	public function mapEmptyOrInvalidToNone($empty)
	{
		$this->assertEquals(new None(), Arr::map($empty, function ($item) { throw new Exception; }));
	}

	/**
	 * @test
	 * @return void
	 */
	public function mapValidToSome()
	{
		$this->assertEquals(
			new Some(array('2', '4', '6')),
			Arr::map(
				array(1,2,3),
				function ($item) {
					return (string)$item * 2;
				}
			)
		);
	}

	/**
	 * @return array
	 */
	public function emptyOrInvalidValues()
	{
		return array(
			array(null),
			array(array()),
			array(0),
			array('')
		);
	}
}

?>