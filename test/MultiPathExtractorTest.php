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
use Ustream\Arr\ExtractorUtils as E;

/**
 * Holds the MultiPathExtractorTest class
 */
class MultiPathExtractorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldExtractAllFieldPathPairsFromDataMap()
    {
        $extractor = new MultiPathExtractor(
            array(
                'a' => E::path('baz'),
                'b' => E::path('foo', 'bar'),
            )
        );
        $this->assertExtracts($extractor, array('a' => 'something', 'b' => 'result'));
    }

    /**
     * @test
     */
    public function shouldReturnNoneWhenAnyFieldIsUnextractable()
    {
        $extractor = new MultiPathExtractor(
            array(
                'a' => E::path('baz'),
                'b' => E::path('unextractable'),
            )
        );
        $this->assertThat($extractor->extract($this->sample), $this->isInstanceOf('Ustream\Option\None'));
    }

    /**
     * @param Extractor $extractor
     * @param string $expected
     */
    private function assertExtracts($extractor, $expected)
    {
        $this->assertEquals($expected, $extractor->extract($this->sample)->getOrElse(null));
    }

    private $sample = array(
        'foo' => array('bar' => 'result'),
        'baz' => 'something',
    );
}
