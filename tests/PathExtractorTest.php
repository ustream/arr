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

namespace Ustream\Arr;

use PHPUnit_Framework_TestCase;
use RuntimeException;

/**
 * Holds the PathExtractorTest class
 */
class PathExtractorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldExtractSimplePathFromDataMap()
    {
        $extractor = new PathExtractor('baz');
        $this->assertExtracts($extractor, 'something');
    }

    /**
     * @test
     */
    public function shouldExtractMultiPartPathFromDataMap()
    {
        $extractor = new PathExtractor(array('foo', 'bar'));
        $this->assertExtracts($extractor, 'result');
    }

    /**
     * @param Extractor $extractor
     * @param string $expected
     */
    private function assertExtracts($extractor, $expected)
    {
        $sample = array(
            'foo' => array('bar' => 'result'),
            'baz' => 'something',
        );
        $this->assertEquals($expected, $extractor->extract($sample)->getOrElse(null));
    }

    /**
     * @test
     */
    public function invalidInput()
    {
        $this->setExpectedException('RuntimeException');
        $extractor = new PathExtractor('asdf');
        $extractor->extract(null)->getOrThrow(new RuntimeException);
    }

    /**
     * @test
     */
    public function extractFirstLevel()
    {
        $extractor = new PathExtractor('asd');
        $this->assertSame(123, $extractor->extract(array('asd' => 123))->getOrThrow(new RuntimeException));
    }

    /**
     * @test
     */
    public function notFoundFirstLevel()
    {
        $this->setExpectedException('RuntimeException');
        $extractor = new PathExtractor('asdf');
        $extractor->extract(array('asd' => 123))->getOrThrow(new RuntimeException);
    }

    /**
     * @test
     */
    public function extractDeeper()
    {
        $data = array(
            'asd' => array(
                'lksd' => array(
                    'mmfs9' => 87681
                )
            )
        );
        $extractor = new PathExtractor('asd.lksd.mmfs9');
        $this->assertSame(87681, $extractor->extract($data)->getOrElse(null));
    }

    /**
     * @test
     */
    public function notDeepEnough()
    {
        $this->setExpectedException('RuntimeException');
        $data = array(
            'asd' => array(
                'lksd' => 1
            )
        );
        $extractor = new PathExtractor('asd.lksd.mmfs9');
        $extractor->extract($data)->getOrThrow(new RuntimeException());
    }

    /**
     * @test
     */
    public function invalidKey()
    {
        $this->setExpectedException('RuntimeException');
        $data = array(
            'asd' => array(
                'lksd' => array(
                    'mmfs9' => 87681
                )
            )
        );
        $extractor = new PathExtractor(null);
        $extractor->extract($data)->getOrThrow(new RuntimeException);
    }
}
