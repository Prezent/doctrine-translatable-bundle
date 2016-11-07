prezent/doctrine-translatable-bundle
====================================

This bundle integrates the [prezent/doctrine-translatable](https://github.com/Prezent/doctrine-translatable) extension in Symfony2.

## Prerequisites

This bundle requires Symfony 2.2+.

## Installation

### Step 1: Download using Composer

Tell Composer to install the bundle:

```bash
$ php composer.phar require prezent/doctrine-translatable-bundle
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

## Integration with Forms and Sonata Admin

You can use the [a2lix/TranslationFormBundle](https://github.com/a2lix/TranslationFormBundle) to integrate the
translatable extension into your own forms and into your Sonata Admin backend. For this to work, you must add
the following method to your translatable entities:

```php
public static function getTranslationEntityClass()
{
    return 'Your\Translation\Class';
}
```

For more information, see the [A2LiX TranslationForm documentation](http://a2lix.fr/bundles/translation-form/). Follow the examples
for the KnpDoctrineExtensions and other indexBy strategies.
