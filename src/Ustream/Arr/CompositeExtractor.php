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

use Ustream\Option\Option;

/**
 * Holds the CompositeExtractor class
 */
class CompositeExtractor implements Extractor
{
    /**
     * @var Extractor
     */
    private $firstExtractor;

    /**
     * @var Extractor
     */
    private $secondExtractor;

    /**
     * @param Extractor $firstExtractor
     * @param Extractor $secondExtractor
     */
    public function __construct($firstExtractor, $secondExtractor)
    {
        $this->firstExtractor = $firstExtractor;
        $this->secondExtractor = $secondExtractor;
    }

    /**
     * @param array $data
     * @return Option
     */
    public function extract($data)
    {
        $secondExtractor = $this->secondExtractor;
        return $this->firstExtractor->extract($data)->apply(
            function ($firstResult) use ($secondExtractor) {
                return $secondExtractor->extract($firstResult);
            }
        );
    }
}
