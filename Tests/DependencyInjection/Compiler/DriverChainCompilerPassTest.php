<?php

namespace Prezent\Doctrine\TranslatableBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Prezent\Doctrine\TranslatableBundle\DependencyInjection\Compiler\DriverChainCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Sander Marechal
 */
class DriverChainCompilerPassTest extends TestCase
{
    public function testDrivers()
    {
        $chain = $this->getMockBuilder(Definition::class)
            ->setMethods(['addMethodCall'])
            ->getMock();

        $chain->expects($this->exactly(2))
            ->method('addMethodCall')
            ->with('addDriver', $this->anything());

        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['getDefinition', 'getParameter'])
            ->getMock();

        $builder->method('getDefinition')->willReturn($chain);
        $builder->method('getParameter')->willReturn([
            'default' => 'doctrine.orm.default_entity_manager',
            'custom' => 'doctrine.orm.custom_entity_manager',
        ]);

        $pass = new DriverChainCompilerPass();
        $pass->process($builder);
    }
}
