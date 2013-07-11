<?php
/**
 * Date: 11.07.13
 * Time: 17:27
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Cache;

/**
 * Class CacheStorageInterface
 * Note: this interface is compatible with sfCache implementations
 *
 * @package Awg\PageSeo\Cache
 */
interface CacheStorageInterface
{
  /**
   * @param string $key
   * @return bool
   */
  public function has($key);

  /**
   * @param string $key
   * @param mixed $data
   * @param null|int $lifetime (in seconds)
   * @return bool true if no problem
   */
  public function set($key, $data, $lifetime = null);

  /**
   * @param $key
   * @param mixed $default
   * @return mixed
   */
  public function get($key, $default = null);
}