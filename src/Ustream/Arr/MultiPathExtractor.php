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

use InvalidArgumentException;
use Ustream\Option\None;
use Ustream\Option\Option;
use Ustream\Option\Some;

/**
 * Holds the MultiPathExtractor class
 */
class MultiPathExtractor implements Extractor
{
    /**
     * @var Extractor[]
     */
    private $extractors;

    /**
     * @param Extractor[] $extractors
     */
    public function __construct($extractors)
    {
        $this->extractors = $extractors;
    }

    /**
     * @param array $data
     * @return Option
     */
    public function extract($data)
    {
        try {
            $result = array();
            foreach ($this->extractors as $field => $extractor) {
                $result[$field] = $extractor->extract($data)->getOrThrow(new InvalidArgumentException());
            }
            return new Some($result);
        } catch (InvalidArgumentException $e) {
            return None::create();
        }
    }
}
