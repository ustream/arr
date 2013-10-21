<?php
/**
 * This file is part of the Arr package.
 *
 * @copyright Ustream Inc.
 * @author pepov <wilcsinszky.peter@ustream.tv>
 * @author blerou <sulik.szabolcs@ustream.tv>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ustream\Arr;

use Ustream\Option\None;
use Ustream\Option\Option;
use Ustream\Option\Some;

/**
 * Holds the PathExtractor class
 */
class PathExtractor implements Extractor
{
    /**
     * @var array
     */
    private $path;

    /**
     * @param string|array $path
     * @param string $separator
     */
    public function __construct($path, $separator = '.')
    {
        if (is_string($path)) {
            $path = explode($separator, $path);
        }
        $this->path = $path;
    }

    /**
     * @param array $data
     * @return Option
     */
    public function extract($data)
    {
        if ($this->path) {
            return $this->extractInner($this->path, $data);
        }
        return None::create();
    }

    private function extractInner($path, $data)
    {
        if (!is_array($data)) {
            return None::create();
        }
        $key = array_shift($path);
        if (!array_key_exists($key, $data)) {
            return None::create();
        } else if ($path) {
            return $this->extractInner($path, $data[$key]);
        } else {
            return new Some($data[$key]);
        }
    }
}
