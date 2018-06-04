<?php

namespace Prezent\Doctrine\TranslatableBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Prezent\Doctrine\TranslatableBundle\DependencyInjection\PrezentDoctrineTranslatableExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PrezentDoctrineTranslatableExtensionTest extends TestCase
{
    public function testDefault()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', []);

        $extension = new PrezentDoctrineTranslatableExtension();
        $extension->load([[]], $container);

        $this->assertTrue($container->hasDefinition('prezent_doctrine_translatable.listener'));

        $listener = $container->getDefinition('prezent_doctrine_translatable.listener');

        $this->assertTrue($listener->hasMethodCall('setCurrentLocale'));
        $this->assertTrue($listener->hasMethodCall('setFallbackLocale'));

        $this->assertFalse($container->hasDefinition('prezent_doctrine_translatable.sonata.filter'));
    }

    public function testSonataFilter()
    {
        $sonataVersion = getenv('SONATA');

        if (!$sonataVersion) {
            $this->markTestSkipped('Sonata is not installed.');
            return;
        }

        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', ['SonataDoctrineORMAdminBundle' => true]);

        $extension = new PrezentDoctrineTranslatableExtension();
        $extension->load([[]], $container);

        if ($sonataVersion == '^3.6') {
            $this->assertTrue($container->hasDefinition('prezent_doctrine_translatable.sonata.filter'));
        } else {
            $this->assertFalse($container->hasDefinition('prezent_doctrine_translatable.sonata.filter'));
        }
    }
}
