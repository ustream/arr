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

class ArrApplyOverrideTest extends PHPUnit_Framework_TestCase
{
    const VALUE = 'value';
    const OLD_VALUE = 'oldValue';

    const KEY = 'key';

    const ANOTHER_KEY = 'key2';

    /**
     * @param array $original
     * @param array $path
     * @param array $expected
     *
     * @dataProvider rules
     *
     * @test
     *
     * @return void
     */
    public function test($original, $path, $expected)
    {
        $this->assertEquals($expected, Arr::applyOverride($original, $path, self::VALUE));
    }

    /**
     * @test
     * @return void
     */
    public function testInvalidAttempt()
    {
        $this->setExpectedException('\InvalidArgumentException');
        Arr::applyOverride(
            array(
                'a',
                'b',
                'c'
            ),
            array('#[1=2]'),
            'asd'
        );
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            'applySingleLevel' => array(
                'original' => array(),
                'path' => array(self::KEY),
                'expected' => array(
                    self::KEY => self::VALUE
                )
            ),
            'applyTwoLevelsDeep' => array(
                'original' => array(),
                'path' => array(self::KEY, self::ANOTHER_KEY),
                'expected' => array(
                    self::KEY => array(
                        self::ANOTHER_KEY => self::VALUE
                    )
                )
            ),
            'override' => array(
                'original' => array(self::KEY => self::OLD_VALUE),
                'path' => array(self::KEY),
                'expected' => array(
                    self::KEY => self::VALUE
                )
            ),
            'overrideDropValueForKey' => array(
                'original' => array(self::KEY => self::OLD_VALUE),
                'path' => array(self::KEY, self::ANOTHER_KEY),
                'expected' => array(
                    self::KEY => array(
                        self::ANOTHER_KEY => self::VALUE
                    )
                )
            ),
            'keepOriginalStructure' => array(
                'original' => array(self::KEY => array(self::OLD_VALUE)),
                'path' => array(self::KEY, self::ANOTHER_KEY),
                'expected' => array(
                    self::KEY => array(
                        0 => self::OLD_VALUE,
                        self::ANOTHER_KEY => self::VALUE
                    )
                )
            ),
            'applyOnRoot' => array(
                'original' => array(self::KEY => array(self::OLD_VALUE)),
                'path' => array(0 => ''),
                'expected' => self::VALUE
            ),
            'walkOnMatcherKey' => array(
                'original' => array(
                    'level1' => array(
                        0 => self::OLD_VALUE,
                        1 => array(
                            'level2' => self::OLD_VALUE,
                            'keyToOverride' => self::OLD_VALUE,
                        ),
                        2 => self::OLD_VALUE
                    )
                ),
                'path' => array('level1', '#[level2=oldValue]', 'keyToOverride'),
                'expected' => array(
                    'level1' => array(
                        0 => self::OLD_VALUE,
                        1 => array(
                            'level2' => self::OLD_VALUE,
                            'keyToOverride' => self::VALUE
                        ),
                        2 => self::OLD_VALUE
                    )
                )
            ),
            'walkOnNumericKey' => array(
                'original' => array(
                    'level1' => array(
                        0 => self::OLD_VALUE,
                        1 => array(
                            'level2' => self::OLD_VALUE
                        ),
                        2 => self::OLD_VALUE
                    )
                ),
                'path' => array('level1', '1', 'level2'),
                'expected' => array(
                    'level1' => array(
                        0 => self::OLD_VALUE,
                        1 => array(
                            'level2' => self::VALUE
                        ),
                        2 => self::OLD_VALUE
                    )
                )
            )
        );
    }
}
