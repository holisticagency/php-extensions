# HolisticAgency/PhpExtensions

PHP Extensions Manager

## Installation

```bash
composer require holistic-agency/php-extensions:dev-main
```

## Usage

```php
use HolisticAgency\PhpExtensions\Builder;

$em = Builder::createManager(
    __DIR__ . '/var/cache/php.extensions.cache',
   __DIR__ . '/var/cache/managed.extensions.cache',
);

if (!$em->keys()) {
    $em->add([
        // ... instances of HolisticAgency\PhpExtensions\ExtensionInterface
    ]);

foreach ($em->keys() as $key) {
    var_dump($em->get($key));
}
```
