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
            $adapter = new Definition('Metadata\\Driver\\DriverInterface', [
                new Reference(sprintf('doctrine.orm.%s_metadata_driver', $name)),
            ]);
            $adapter->setFactoryClass('Prezent\\Doctrine\\Translatable\\Mapping\\Driver\\DoctrineAdapter');
            $adapter->setFactoryMethod('fromMetadataDriver');

            $driver->addMethodCall('addDriver', [$adapter]);
        }
    }
}
