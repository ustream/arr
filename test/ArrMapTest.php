<?php
/**
 * This file is part of the Arr package.
 *
 * @copyright Ustream Inc.
 * @author pepov <wilcsinszky.peter@ustream.tv>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ustream\Arr;

use Exception;
use PHPUnit_Framework_TestCase;
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
        $this->assertEquals(
            None::create(),
            Arr::map(
                $empty,
                function ($item) {
                    throw new Exception;
                }
            )
        );
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
                array(1, 2, 3),
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
