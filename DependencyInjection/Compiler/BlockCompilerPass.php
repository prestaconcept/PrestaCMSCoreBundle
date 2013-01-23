<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class BlockCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('presta_cms.block_manager')) {
            return;
        }

        $definition = $container->getDefinition(
            'presta_cms.block_manager'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'presta_cms.block'
        );
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addBlock',
                array($id)
            );
        }


    }

}