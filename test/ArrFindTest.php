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

use PHPUnit_Framework_TestCase;

class ArrFindTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @return void
     */
    public function findItemAndReturn()
    {
        $processed = array();
        $found = Arr::find(
            array(1, 2, 3, 4),
            function ($item) use (&$processed) {
                $processed[] = $item;
                return $item == 3;
            }
        );
        $this->assertEquals(3, $found->get());
        $this->assertEquals($processed, array(1, 2, 3), 'The item 4 must not get processed');
    }

    /**
     * @test
     * @return void
     */
    public function findsNot()
    {
        $found = Arr::find(
            array(1, 2, 3, 4),
            function ($item) use (&$processed) {
                return $item == 1234;
            }
        );
        $this->assertFalse($found->exists());
    }
}
