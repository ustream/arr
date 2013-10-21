# Ustream Arr [![Build Status](https://travis-ci.org/ustream/arr.png?branch=master)](http://travis-ci.org/ustream/arr)

This is a collection of Array related utilities.

### Extractors

Mining data from a deeply nested array is tedious. The _Extractor library_ give a programable and composable solution to this kind of problems.

Reaching data on a simple "path" in the nested structure:
```php
$sample = array(
  "foo" => array(
    "bar" => array(
      "baz" => "something"
    )
  )
);

$extractor = new PathExtractor(array("foo", "bar", "baz"));
$result = $extractor->extract($sample);
//        "something" # inside an Option instance

$extractor = new PathExtractor(array("foo", "bar"));
$result = $extractor->extract($sample);
//        array("baz" => "something") # inside an Option instance

$extractor = new PathExtractor(array("foo"));
$result = $extractor->extract($sample);
//        array("bar" => array("baz" => "something")) # inside an Option instance
```
