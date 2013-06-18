doctrine-translatable-bundle
============================

Integrate the doctrine-translatable extension in Symfony2.

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

You can optionlly configure the fallback mode (default: off) and fallback locale (default: en).
The current locale is automatically set from the request.

```yaml
prezent_doctrine_translatable:
    fallbackMode: false
    fallbackLocale: en
```