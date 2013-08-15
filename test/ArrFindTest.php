<?php
/**
 * @author pepov <pepov@ustream.tv>
 */

use Ustream\Arr\Arr;

/**
 * ArrFindTest
 */
class ArrFindTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 * @return void
	 */
	public function findItemAndReturn()
	{
		$processed = array();
		$found = Arr::find(array(1,2,3,4), function ($item) use (&$processed) {
			$processed[] = $item;
			return $item == 3;
		});
		$this->assertEquals(3, $found->get());
		$this->assertEquals($processed, array(1,2,3), 'The item 4 must not get processed');
	}

	/**
	 * @test
	 * @return void
	 */
	public function findsNot()
	{
		$found = Arr::find(array(1,2,3,4), function ($item) use (&$processed) {
			return $item == 1234;
		});
		$this->assertFalse($found->exists());
	}
}

?>