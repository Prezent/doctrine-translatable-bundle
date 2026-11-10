<?php

/*
 * (c) Prezent Internet B.V. <info@prezent.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('prezent_doctrine_translatable.listener.class', 'Prezent\Doctrine\Translatable\EventListener\TranslatableListener')
        ->set('prezent_doctrine_translatable.listener.locale.class', 'Prezent\Doctrine\TranslatableBundle\EventListener\LocaleListener')
        ->set('prezent_doctrine_translatable.metadata_factory.class', 'Metadata\MetadataFactory')
        ->set('prezent_doctrine_translatable.driver_chain.class', 'Metadata\Driver\DriverChain');

    $services = $container->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set('prezent_doctrine_translatable.driver_chain', '%prezent_doctrine_translatable.driver_chain.class%');

    $services->set('prezent_doctrine_translatable.metadata_factory', '%prezent_doctrine_translatable.metadata_factory.class%')
        ->args([service('prezent_doctrine_translatable.driver_chain')]);

    $services->set('prezent_doctrine_translatable.listener', '%prezent_doctrine_translatable.listener.class%')
        ->args([service('prezent_doctrine_translatable.metadata_factory')])
        ->tag('doctrine.event_listener', ['event' => 'loadClassMetadata', 'connection' => 'default'])
        ->tag('doctrine.event_listener', ['event' => 'postLoad', 'connection' => 'default']);

    $services->set('prezent_doctrine_translatable.listener.locale', '%prezent_doctrine_translatable.listener.locale.class%')
        ->args([service('prezent_doctrine_translatable.listener')])
        ->tag('kernel.event_subscriber');
};
