<?php
/**
 * Date: 09.07.13
 * Time: 18:02
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration;


class MatcherProviderDecoratorTest extends \PHPUnit_Framework_TestCase
{
  public function testBasics()
  {
    $arr = array(
      'faq' => array(
        'title' => 'FAQ',
      ),
      'forum' => array(
        'title' => 'Forum',
      ),
      'page' => array(
        'title' => 'Homepage',
      ),
      'page?path=about' => array(
        'title' => 'About us'
      ),
      'country?code=us' => array(
        'title' => 'United States'
      ),
      'country?code=fr&culture=fr' => array(
        'title' => 'France'
      )
    );
    $config = new MatcherProviderDecorator($arr);

    $this->assertEquals(array_keys(iterator_to_array($config)), array_keys($arr), 'Class implements iterator interface');

    // Direct matching
    $this->assertEquals($config['faq']['title'], 'FAQ', 'faq');
    $this->assertEquals($config['forum']['title'], 'Forum', 'forum');
    $this->assertEquals($config['page']['title'], 'Homepage', 'page');
    $this->assertEquals($config['page?path=about']['title'], 'About us', 'page?path=about');
    $this->assertEquals($config['country?code=us']['title'], 'United States', 'country?code=us');

    // Query string matching - using fallback
    // fallback to more general configuration
    $this->assertEquals($config['faq?lang=en']['title'], 'FAQ', "faq?lang=en");
    $this->assertEquals($config['page?path=another']['title'], 'Homepage', "page?path=another");
    $this->assertEquals($config['page?path=about&lang=en']['title'], 'About us', "page?path=about&lang=en");

    // cannot fallback
    // Query string matching - cannot use fallback
    $this->assertTrue(!isset($config['country']), 'country');
    $this->assertTrue(!isset($config['coutry']), 'misspelled');
    $this->assertTrue(!isset($config['country?code=ru']), 'country?code=ru');
    $this->assertTrue(!isset($config['country?tail=something']), 'country?tail=something');
    $this->assertTrue(!isset($config['country?code=fr']), 'country?code=fr');
    $this->assertEquals($config['country?code=fr&culture=fr']['title'], 'France', 'query string exact match');
  }
}