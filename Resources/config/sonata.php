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
        ->set('prezent_doctrine_translatable.sonata.filter.class', 'Prezent\Doctrine\TranslatableBundle\Filter\TranslatableFilter');

    $services = $container->services();

    $services->set('prezent_doctrine_translatable.sonata.filter', '%prezent_doctrine_translatable.sonata.filter.class%')
        ->args([service('prezent_doctrine_translatable.listener')])
        ->tag('sonata.admin.filter.type', ['alias' => 'translatable']);
};
