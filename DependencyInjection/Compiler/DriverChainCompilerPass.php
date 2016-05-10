<?php

namespace Prezent\Doctrine\TranslatableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compile the driverchain
 *
 * @author Sander Marechal
 */
class DriverChainCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $driver = $container->getDefinition('prezent_doctrine_translatable.driver_chain');

        foreach ($container->getParameter('doctrine.entity_managers') as $name => $manager) {
            $adapter = new Definition(
               'Metadata\\Driver\\DriverInterface', 
                array(
                    new Reference(sprintf('doctrine.orm.%s_metadata_driver', $name)),
                )
            );

            $class = 'Prezent\\Doctrine\\Translatable\\Mapping\\Driver\\DoctrineAdapter';
            $method = 'fromMetadataDriver';

            if (method_exists($adapter, 'setFactory')) {
                $adapter->setFactory([$class, $method]);
            } else {
                $adapter->setFactoryClass($class);
                $adapter->setFactoryMethod($method);
            }

            $driver->addMethodCall('addDriver', array($adapter));
        }
    }
}
