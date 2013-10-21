prezent/doctrine-translatable-bundle
====================================

This bundle integrates the [prezent/doctrine-translatable](https://github.com/Prezent/doctrine-translatable) extension in Symfony2.

## Prerequisites

This bundle requires Symfony 2.2+.

## Installation

### Step 1: Download using Composer

Add this bundle to your composer.json:

```js
{
    "require": {
        "prezent/doctrine-translatable-bundle": "dev-master"
    }
}
```

Tell Composer to install the bundle:

```bash
$ php composer.phar update prezent/doctrine-translatable-bundle
```

### Step 2: Enable the bundle

Enable this bundle in your kernel

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Prezent\Doctrine\TranslatableBundle\PrezentDoctrineTranslatableBundle(),
    );
}
```

### Step 3: Configuration

You can optionlly configure the fallback locale (default: en).
The current locale is automatically set from the current Request. It defaults to the
fallback\_locale which you can configure in your config.yml.

```yaml
prezent_doctrine_translatable:
    fallback_locale: en
```

## Usage

Please refer to the [doctrine-translatable documentation](https://github.com/Prezent/doctrine-translatable/blob/master/doc/index.md)
on how to use the translatable extension.
