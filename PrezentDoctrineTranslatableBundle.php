<?php

/*
 * (c) Prezent Internet B.V. <info@prezent.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prezent\Doctrine\TranslatableBundle;

use Prezent\Doctrine\TranslatableBundle\DependencyInjection\Compiler\DriverChainCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * PrezentDoctrineTranslatableBundle
 *
 * @see Bundle
 */
class PrezentDoctrineTranslatableBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DriverChainCompilerPass());
    }
}
