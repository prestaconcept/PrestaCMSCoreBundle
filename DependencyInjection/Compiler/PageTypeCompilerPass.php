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

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageTypeCompilerPass implements CompilerPassInterface
{
    /**
     * {@inherited}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('presta_cms.manager.page')) {
            return;
        }

        $definition = $container->getDefinition(
            'presta_cms.manager.page'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'presta_cms.page_type'
        );
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addPageType',
                array($id)
            );
        }
    }
}
