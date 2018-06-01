<?php

/*
 * (c) Prezent Internet B.V. <info@prezent.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prezent\Doctrine\TranslatableBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PrezentDoctrineTranslatableExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->getDefinition('prezent_doctrine_translatable.listener')
                  ->addMethodCall('setCurrentLocale', array($config['fallback_locale']))
                  ->addMethodCall('setFallbackLocale', array($config['fallback_locale']));

        $this->loadSonata($container, $loader);
    }

    /**
     * Load the Sonata configuration, if the versions is supported
     *
     * @param ContainerBuilder $container
     * @param Loader\XmlFileLoader $loader
     */
    private function loadSonata(ContainerBuilder $container, Loader\XmlFileLoader $loader)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (!isset($bundles['SonataDoctrineORMAdminBundle'])) {
            return; // Sonata not installed
        }

        $refl = new \ReflectionMethod('Sonata\\DoctrineORMAdminBundle\\Filter\\StringFilter', 'filter');

        if (method_exists($refl, 'getReturnType')) {
            if ($returnType = $refl->getReturnType()) {
                return; // Not compatible with this Sonata version
            }
        }

        $loader->load('sonata.xml');
    }
}
