<?php
/**
 * This file is part of the Arr package.
 *
 * @copyright Ustream Inc.
 * @author pepov <pepov@ustream.tv>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ustream\Arr;

use PHPUnit_Framework_TestCase;
use RuntimeException;

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
        $expected = 87681;
        $this->assertSame(
            $expected,
            Arr::extract(
                array(
                    'asd' => array(
                        'lksd' => array(
                            'mmfs9' => $expected
                        )
                    )
                ),
                'asd.lksd.mmfs9'
            )->getOrElse(null)
        );
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
            ),
            'asd.lksd.mmfs9'
        )->getOrThrow(new RuntimeException());
    }

    /**
     * @test
     */
    public function invalidKey()
    {
        $this->setExpectedException('RuntimeException');
        Arr::extract(
            array(
                'asd' => array(
                    'lksd' => array(
                        'mmfs9' => 87681
                    )
                )
            ),
            null
        )->getOrThrow(new RuntimeException);
    }
}
