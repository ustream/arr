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

use InvalidArgumentException;
use Ustream\Option\Option;
use Ustream\Option\None;
use Ustream\Option\Some;

class Arr
{
    /**
     * Returns value from arbitrary depth in array using keys separated with periods.
     *
     * e.g.:
     *   extract(array('a' => array('b' => 'c')), 'a.b') will return: new Some('c')
     *
     * or with custom separator:
     *   extract(array('a' => array('b' => 'c')), 'a/b', '/') will return: new Some('c')
     *
     * or keys defined as an array:
     *   extract(array('a' => array('b' => 'c')), array('a', 'b')) will return: new Some('c')
     *
     * @param array $array
     * @param string|array $keys
     * @param string $separator
     *
     * @return Option
     */
    public static function extract($array, $keys, $separator = '.')
    {
        if (!is_array($array)) {
            return new None();
        }
        if (!is_array($keys) && !is_string($keys)) {
            return new None();
        }

        if (is_string($keys) && array_key_exists($keys, $array)) {
            return new Some($array[$keys]);
        }

        $keys = is_array($keys) ? $keys : explode($separator, $keys);
        if (!count($keys)) {
            return new None();
        }
        $key = array_shift($keys);

        if (array_key_exists($key, $array)) {
            if (count($keys)) {
                return self::extract($array[$key], $keys);
            } else {
                return new Some($array[$key]);
            }
        } else {
            return new None();
        }
    }

    /**
     * provides lazy method of filtering and slicing an array
     *
     * @param array $ary
     * @param callable $filter
     * @param int $offset
     * @param int $length
     * @return Option
     */
    public static function filterSlice($ary, $filter, $offset = 0, $length = null)
    {
        $result = array();
        $curr = 0;
        foreach ($ary as $el) {
            if (!call_user_func($filter, $el)) {
                continue;
            }
            if ($curr++ < $offset) {
                continue;
            }
            if ((is_null($length) || count($result) < $length)) {
                $result[] = $el;
            }
        }
        return $result ? new Some($result) : new None();
    }

    /**
     * Find an item in a multidimensional array and replace it with value
     *
     * @param array $original
     * @param array $path
     * @param mixed $value
     * @throws InvalidArgumentException
     * @return array
     */
    public static function applyOverride($original, $path, $value)
    {
        $path = (array)$path;
        $level = count($path);

        if ($level == 0) {
            return $original;
        }

        $key = array_shift($path);

        if ($level === 1 && $key === '') {
            return $value;
        }

        $baseOnCurrentLevel = is_array($original) ? $original : array();

        if (strncmp($key, '#', 1) === 0) {
            $matches = array();
            if (preg_match('/#\[(?P<matcherKey>\w+)=(?P<matcherVal>[a-zA-Z_-]+)\]/', $key, $matches)) {
                $matcherKey = $matches['matcherKey'];
                $matcherVal = $matches['matcherVal'];
                $matcher = function ($value) use ($matcherKey, $matcherVal) {
                    return is_array($value) && isset($value[$matcherKey]) && $value[$matcherKey] == $matcherVal;
                };
                $key = self::findKey($baseOnCurrentLevel, $matcher)->getOrThrow(new InvalidArgumentException('Cannot found matching list item for ' . $key));
            } else {
                throw new InvalidArgumentException('Cannot found matching list item for ' . $key);
            }
        }

        if ($level == 1) {
            $baseOnCurrentLevel[$key] = $value;
            return $baseOnCurrentLevel;
        }

        $baseForNextLevel = is_array($original) && array_key_exists($key, $original) ? $original[$key] : array();

        $baseOnCurrentLevel[$key] = self::applyOverride($baseForNextLevel, $path, $value);
        return $baseOnCurrentLevel;
    }

    /**
     * @param array $original
     * @param callable $mapper
     *
     * @return Option
     */
    public static function map($original, $mapper)
    {
        if (is_array($original) && count($original)) {
            return new Some(array_map($mapper, $original));
        }
        return new None();
    }

    /**
     * @param array $array
     * @param callable $matcher
     *
     * @return Option
     */
    public static function find($array, $matcher)
    {
        if (is_array($array) && count($array)) {
            foreach ($array as $key => $value) {
                if (call_user_func($matcher, $value)) {
                    return new Some($value);
                }
            }
        }
        return new None();
    }

    /**
     * @param array $array
     * @param callable $matcher
     *
     * @return Option
     */
    public static function findKey($array, $matcher)
    {
        if (is_array($array) && count($array)) {
            foreach ($array as $key => $value) {
                if (call_user_func($matcher, $value)) {
                    return new Some($key);
                }
            }
        }
        return new None();
    }
}
