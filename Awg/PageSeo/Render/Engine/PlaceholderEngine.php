<?php
/**
 * Date: 28.02.13
 * Time: 18:59
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Render\Engine;

use Awg\PageSeo\Exception\UndefinedPlaceholderException;

class PlaceholderEngine implements EngineInterface
{
  protected static $cache = array();

  const ANY_CALL    = 'any';
  const ARRAY_CALL  = 'array';
  const METHOD_CALL = 'method';

  protected $prefixes;

  protected $strict;

  function __construct($prefixes = array('seo', ''), $strict = true)
  {
    $this->prefixes = $prefixes;
    $this->strict = $strict;
  }


  /**
   * Returns the attribute value for a given array/object.
   *
   * @param mixed   $object            The object or array from where to get the item
   * @param mixed   $item              The item to get from the array or object
   * @param array   $arguments         An array of arguments to pass if the item is an object method
   * @param string  $type              The type of attribute (@see rcStringUtils::ANY_CALL and others)
   * @param Boolean $ignoreStrictCheck Whether to ignore the strict attribute check or not
   *
   * @throws \Awg\PageSeo\Exception\UndefinedPlaceholderException
   * @return mixed The attribute value, or a Boolean when $isDefinedTest is true, or null when the attribute is not set and $ignoreStrictCheck is true
   *
   */
  protected function getAttribute($object, $item, array $arguments = array(), $type = self::ANY_CALL, $ignoreStrictCheck = false)
  {
    $item = ctype_digit((string) $item) ? (int) $item : (string) $item;

    // array
    if (self::METHOD_CALL !== $type) {
      if ((is_array($object) && array_key_exists($item, $object))
        || ($object instanceof \ArrayAccess && isset($object[$item]))
      ) {
        return $object[$item];
      }

      if (self::ARRAY_CALL === $type) {

        if ($ignoreStrictCheck) {
          return null;
        }

        if (is_object($object)) {
          throw new UndefinedPlaceholderException(sprintf('Key "%s" in object (with ArrayAccess) of type "%s" does not exist', $item, get_class($object)), $item);
        } elseif (is_array($object)) {
          throw new UndefinedPlaceholderException(sprintf('Key "%s" for array with keys "%s" does not exist', $item, implode(', ', array_keys($object))), $item);
        } else {
          throw new UndefinedPlaceholderException(sprintf('Impossible to access a key ("%s") on a "%s" variable', $item, gettype($object)), $item);
        }
      }
    }

    if (!is_object($object)) {
      if ($ignoreStrictCheck) {
        return null;
      }

      throw new UndefinedPlaceholderException(sprintf('Item "%s" for "%s" does not exist', $item, is_array($object) ? 'Array' : $object));
    }

    // object property
    if (self::METHOD_CALL !== $type) {
      if (isset($object->$item) || array_key_exists($item, $object)) {

        return $object->$item;
      }
    }

    $class = get_class($object);

    // object method
    if (!isset(self::$cache[$class]['methods'])) {
      self::$cache[$class]['methods'] = array_change_key_case(array_flip(get_class_methods($object)));
    }

    foreach ($this->prefixes as $prefix)
    {
      $lcItem = strtolower($prefix.$item);
      $lcCcItem = strtolower($prefix.strtr($item, array('-'=>'', '_'=>''))); // lower case camel cased (removed _ and -)
      $variants = ($lcItem == $lcCcItem) ? array($lcItem) : array($lcCcItem, $lcItem);

      foreach ($variants as $name)
      {
        if (isset(self::$cache[$class]['methods'][$name])) {
          $method = $name;
        } elseif (isset(self::$cache[$class]['methods']['get'.$name])) {
          $method = 'get'.$name;
        } elseif (isset(self::$cache[$class]['methods']['is'.$name])) {
          $method = 'is'.$name;
        }
      }

      if (isset($method)) {
        break;
      }
    }

    if (!isset($method))
    {
      if (isset(self::$cache[$class]['methods']['__call'])) {
        $method = $item;
      } else {
        if ($ignoreStrictCheck) {
          return null;
        }
        throw new UndefinedPlaceholderException(sprintf('Method "%s" for object "%s" does not exist', $item, get_class($object)), $item);
      }
    }

    $ret = call_user_func_array(array($object, $method), $arguments);

    return $ret;
  }

  /**
   * @param string $string
   * @param mixed $context
   * @throws \Awg\PageSeo\Exception\UndefinedPlaceholderException
   * @return mixed|string
   */
  public function renderString($string, $context)
  {
    $parts = preg_match_all('/%[^%]+%/', $string, $matches);
    foreach ($matches[0] as $match)
    {
      $field = trim(substr($match, 1, -1));

      $path = explode('.', $field);
      $current = $context;

      try
      {
        foreach ($path as $part)
        {
          $current = self::getAttribute($current, $part, array(), self::ANY_CALL, !$this->strict);
        }
        $string = str_replace($match, (string)$current, $string);
      }
      catch (UndefinedPlaceholderException $e)
      {
        throw new UndefinedPlaceholderException(sprintf('Error rendering "%s": %s', $field, $e->getMessage()), $field, $e);
      }
    }

    return $string;
  }
}
