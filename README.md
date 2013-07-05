Page Seo library
================

Library to simplify managing and retrieving seo strings configurations from a config array

Installation
------------

Use [Composer](http://getcomposer.org/). Just add this dependency to your `composer.json`:

```json
  "require": {
    "awg/page-seo-lib": "dev-master"
  }
```

Usage example
-------------

```php
    $configuration = new \Awg\PageSeo\Configuration\InheritanceArrayProvider(array(
      'route1' => array(
        'title' => 'Route 1: Hello, %name%',
        'keywords' => 'Common Route Keywords'
      ),
      'route2' => array(
        'inherit' => '@route1',
        'title' => 'Route 2: Hello, %name%'
      )
    ), array(
      'description' => 'Default Route Description'
    ));
    $renderer = new \Awg\PageSeo\Render\BasicRenderer(new \Awg\PageSeo\Render\Engine\PlaceholderEngine());
    $manager = new \Awg\PageSeo\Manager($configuration, $renderer);

    var_dump($manager->renderTitle('route1', array('name' => 'John Doe')));
    var_dump($manager->renderKeywords('route2', array()));
    var_dump($manager->renderDescription('route2', array()));
```